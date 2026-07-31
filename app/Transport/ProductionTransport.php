<?php

declare(strict_types=1);

namespace PsycheAI\Transport;

use PsycheAI\Transport\DTO\FileTransportResult;
use PsycheAI\Transport\DTO\TransportRunOutcome;
use PsycheAI\Transport\Ftp\FtpClientInterface;

/**
 * Publica psycheai-app/ (este repositório, já com `composer install
 * --no-dev` rodado localmente) numa conta FTP restrita da Hostinger, via
 * FTPS explícito — mesmo transporte usado por Collector369/Sonus AI,
 * porque esta conta não tem SSH (`u196460065`, shell `/sbin/nologin`).
 *
 * Diferença central para o Collector369: ali era um arquivo por provider;
 * aqui é uma árvore inteira (app/, bin/, config/, public/, vendor/,
 * composer.json, composer.lock). `storage/` nunca é sincronizado a partir
 * daqui — só tem seus três subdiretórios (cache, data, logs) garantidos
 * (criados se ainda não existirem), porque é onde vive o SQLite e o áudio
 * gravado de sujeitos reais em produção; sobrescrever isso a cada deploy
 * apagaria dados de sessões de análise já em andamento.
 *
 * Diferente do Collector369 também na resolução de "já existe remoto com
 * tamanho diferente": lá isso é `conflict` (aborta, exige investigação
 * humana), porque cada nome de arquivo já embute um timestamp — uma
 * colisão de nome ali é uma anomalia real. Aqui os caminhos são fixos
 * (é código, não dado com timestamp): tamanho diferente é simplesmente
 * "o arquivo mudou desde o último deploy", o caso normal e esperado de
 * uma redeploy. O local (git-controlado) é sempre a fonte da verdade e
 * sobrescreve — pela mesma rotina segura de `.tmp` + verificação de hash
 * + rename usada para arquivos novos.
 */
final class ProductionTransport
{
    private const TMP_PREFIX = '.tmp_';

    /** @var list<string> */
    private const INCLUDED_TOP_LEVEL_DIRS = ['app', 'bin', 'config', 'public', 'vendor'];

    /** @var list<string> */
    private const INCLUDED_TOP_LEVEL_FILES = ['composer.json', 'composer.lock'];

    private const STORAGE_DIR = 'storage';

    /** @var list<string> */
    private const PROTECTED_STORAGE_SUBDIRS = ['cache', 'data', 'logs'];

    /**
     * @param array<int, int> $retryDelaysSeconds espera (segundos) entre tentativas de conexão; a quantidade de tentativas é count($retryDelaysSeconds) + 1
     * @param int $reconnectEveryFiles a conta FTP da Hostinger derruba a sessão depois de um número de comandos/conexões de dados PASV (descoberto empiricamente: duas execuções reais pararam por volta do mesmo ponto, entre ~150 e ~300 comandos, independente de quais arquivos). Com milhares de arquivos numa única sessão isso sempre estoura, então a conexão é recriada periodicamente em vez de mantida do início ao fim.
     */
    public function __construct(
        private readonly string $localRoot,
        private readonly FtpClientInterface $ftp,
        private readonly string $host,
        private readonly int $port,
        private readonly string $user,
        private readonly string $password,
        private readonly string $remoteRoot = '/',
        private readonly int $connectTimeoutSeconds = 10,
        private readonly array $retryDelaysSeconds = [2, 5],
        private readonly int $reconnectEveryFiles = 20,
    ) {
    }

    /**
     * @param (callable(FileTransportResult): void)|null $onProgress chamado após cada item processado — a árvore tem milhares de arquivos (vendor/), então o chamador precisa de feedback incremental, não só do resultado agregado no final.
     */
    public function run(?callable $onProgress = null): TransportRunOutcome
    {
        if (!$this->connectWithRetry()) {
            return new TransportRunOutcome(false, []);
        }

        $results = [];
        $processedSinceConnect = 0;
        $emit = function (FileTransportResult $result) use (&$results, &$processedSinceConnect, $onProgress): void {
            $results[] = $result;
            if ($onProgress !== null) {
                $onProgress($result);
            }

            $processedSinceConnect++;
            if ($processedSinceConnect >= $this->reconnectEveryFiles) {
                $this->ftp->close();
                $this->connectWithRetry();
                $processedSinceConnect = 0;
            }
        };

        foreach ($this->ensureProtectedStorageDirs() as $result) {
            $emit($result);
        }

        foreach (self::INCLUDED_TOP_LEVEL_FILES as $file) {
            $localFile = $this->localRoot . '/' . $file;
            if (is_file($localFile)) {
                $emit($this->transferOneFile($localFile, $file));
            }
        }

        foreach (self::INCLUDED_TOP_LEVEL_DIRS as $dir) {
            $localDir = $this->localRoot . '/' . $dir;
            if (is_dir($localDir)) {
                $this->transferDirectory($localDir, $dir, $emit);
            }
        }

        $this->ftp->close();

        return new TransportRunOutcome(true, $results);
    }

    private function connectWithRetry(): bool
    {
        $maxAttempts = count($this->retryDelaysSeconds) + 1;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            if ($attempt > 1) {
                sleep($this->retryDelaysSeconds[$attempt - 2]);
            }

            if ($this->ftp->connect($this->host, $this->port, $this->connectTimeoutSeconds)
                && $this->ftp->login($this->user, $this->password)
            ) {
                return true;
            }

            $this->ftp->close();
        }

        return false;
    }

    /**
     * @return list<FileTransportResult>
     */
    private function ensureProtectedStorageDirs(): array
    {
        $this->ftp->mkdir($this->remotePath(self::STORAGE_DIR));

        $results = [];
        foreach (self::PROTECTED_STORAGE_SUBDIRS as $subdir) {
            $relativePath = self::STORAGE_DIR . '/' . $subdir;
            $this->ftp->mkdir($this->remotePath($relativePath));
            $results[] = FileTransportResult::storageDirEnsured($relativePath);
        }

        return $results;
    }

    /**
     * @param callable(FileTransportResult): void $emit
     */
    private function transferDirectory(string $localDir, string $relativeDir, callable $emit): void
    {
        $this->ftp->mkdir($this->remotePath($relativeDir));

        $entries = scandir($localDir) ?: [];
        sort($entries);

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $localPath = $localDir . '/' . $entry;
            $relativePath = $relativeDir . '/' . $entry;

            if (is_dir($localPath)) {
                $this->transferDirectory($localPath, $relativePath, $emit);

                continue;
            }

            $emit($this->transferOneFile($localPath, $relativePath));
        }
    }

    private function transferOneFile(string $localFile, string $relativePath): FileTransportResult
    {
        $remoteFinal = $this->remotePath($relativePath);
        $remoteTmp = $this->remotePath(dirname($relativePath) . '/' . self::TMP_PREFIX . basename($relativePath));

        $this->cleanupOrphanTmp($remoteTmp);

        $localSize = filesize($localFile);
        $remoteSize = $this->ftp->size($remoteFinal);

        if ($remoteSize >= 0 && $remoteSize === $localSize) {
            return FileTransportResult::alreadyCurrent($relativePath);
        }

        return $this->uploadViaTemporaryFile($localFile, $relativePath, $remoteTmp, $remoteFinal, $localSize);
    }

    private function cleanupOrphanTmp(string $remoteTmp): void
    {
        if ($this->ftp->size($remoteTmp) < 0) {
            return;
        }

        $this->ftp->delete($remoteTmp);
    }

    private function uploadViaTemporaryFile(
        string $localFile,
        string $relativePath,
        string $remoteTmp,
        string $remoteFinal,
        int $localSize,
    ): FileTransportResult {
        if (!$this->ftp->put($localFile, $remoteTmp)) {
            return FileTransportResult::error($relativePath, "Falha ao enviar arquivo para {$remoteTmp} (ftp_put).");
        }

        $tmpSize = $this->ftp->size($remoteTmp);
        if ($tmpSize !== $localSize || !$this->contentsMatch($localFile, $remoteTmp)) {
            $this->ftp->delete($remoteTmp);

            return FileTransportResult::error(
                $relativePath,
                "Falha de integridade ao validar upload de {$relativePath} — arquivo temporário removido, nada foi publicado.",
            );
        }

        if (!$this->ftp->rename($remoteTmp, $remoteFinal)) {
            return FileTransportResult::error($relativePath, "Falha ao renomear arquivo temporário para o nome final ({$remoteFinal}).");
        }

        return FileTransportResult::transported($relativePath, $localSize);
    }

    private function contentsMatch(string $localFile, string $remotePath): bool
    {
        $downloaded = tempnam(sys_get_temp_dir(), 'psycheai-transport-check-');
        if ($downloaded === false) {
            return false;
        }

        $ok = $this->ftp->get($remotePath, $downloaded);
        $match = $ok && hash_file('sha256', $downloaded) === hash_file('sha256', $localFile);

        unlink($downloaded);

        return $match;
    }

    private function remotePath(string $relativePath): string
    {
        return rtrim($this->remoteRoot, '/') . '/' . $relativePath;
    }
}
