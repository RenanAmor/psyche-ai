<?php

declare(strict_types=1);

namespace PsycheAI\Transport\DTO;

/**
 * Resultado do transporte de um item individual (arquivo ou diretório
 * protegido) para produção.
 *
 * status possíveis:
 * - transported: arquivo enviado (novo ou substituindo um remoto de
 *   conteúdo diferente) e verificado com sucesso — local é sempre a fonte
 *   da verdade, então conteúdo remoto diferente do local é tratado como
 *   "o arquivo mudou desde o último deploy" e sobrescrito, não como
 *   conflito (diferente do transporte do Collector369, onde cada nome de
 *   arquivo já embute um timestamp e uma colisão de nome é uma anomalia
 *   real que exige investigação humana — aqui os caminhos são fixos,
 *   é código versionado em git, e mudar é o caso normal de um redeploy).
 * - already_current: produção já tinha exatamente o mesmo conteúdo do
 *   arquivo local, confirmado por hash SHA-256 (baixa o remoto e compara
 *   byte a byte). Uma versão anterior pulava esse download quando o
 *   tamanho já batia, por performance — causou um bug real em produção
 *   (`vendor/composer/platform_check.php` mudou de conteúdo sem mudar de
 *   tamanho e ficou desatualizado silenciosamente por várias execuções),
 *   então a verificação por hash agora é sempre feita, com o custo de uma
 *   chamada FTP a mais por arquivo já existente.
 * - error: falha técnica (conexão, integridade pós-upload, rename etc.).
 * - storage_dir_ensured: subdiretório protegido de storage/ garantido
 *   (criado ou já existente) — nenhum arquivo é tocado dentro dele.
 */
final class FileTransportResult
{
    private function __construct(
        public readonly string $relativePath,
        public readonly string $status,
        public readonly string $message,
        public readonly ?int $bytes = null,
    ) {
    }

    public static function transported(string $relativePath, int $bytes): self
    {
        return new self(
            $relativePath,
            'transported',
            "Arquivo transportado e verificado com sucesso ({$bytes} bytes).",
            $bytes,
        );
    }

    public static function alreadyCurrent(string $relativePath): self
    {
        return new self(
            $relativePath,
            'already_current',
            'Produção já está atualizada (mesmo conteúdo do arquivo local, confirmado por hash).',
        );
    }

    public static function error(string $relativePath, string $message): self
    {
        return new self($relativePath, 'error', $message);
    }

    public static function storageDirEnsured(string $relativePath): self
    {
        return new self($relativePath, 'storage_dir_ensured', 'Diretório protegido garantido — conteúdo nunca é sobrescrito.');
    }
}
