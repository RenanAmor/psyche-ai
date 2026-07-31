<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PsycheAI\Transport\DTO\FileTransportResult;
use PsycheAI\Transport\Ftp\NativeFtpClient;
use PsycheAI\Transport\ProductionTransport;

/**
 * Publica este repositório (já com `composer install --no-dev` rodado
 * localmente) na conta FTP restrita da Hostinger que serve
 * investimentos369.com/psycheai — não há SSH nesta hospedagem, então FTPS
 * é o único transporte possível, mesmo caminho já usado pelo Collector369.
 * Ver docs/architecture/Transporte-Producao-PsycheAI.md.
 *
 * Uso: php bin/transportar-producao.php
 */

carregarDotenvLocal(__DIR__ . '/../.env');

$host = getenv('PRODUCTION_FTP_HOST') ?: '';
$user = getenv('PRODUCTION_FTP_USER') ?: '';
$password = getenv('PRODUCTION_FTP_PASSWORD') ?: '';

if ($host === '' || $user === '' || $password === '') {
    fwrite(STDERR, "Faltam PRODUCTION_FTP_HOST / PRODUCTION_FTP_USER / PRODUCTION_FTP_PASSWORD no .env.\n");

    exit(2);
}

$transport = new ProductionTransport(
    localRoot: __DIR__ . '/..',
    ftp: new NativeFtpClient(),
    host: $host,
    port: (int) (getenv('PRODUCTION_FTP_PORT') ?: 21),
    user: $user,
    password: $password,
    remoteRoot: getenv('PRODUCTION_FTP_REMOTE_PATH') ?: '/',
);

$contadores = ['transported' => 0, 'already_current' => 0, 'conflict' => 0, 'error' => 0, 'storage_dir_ensured' => 0];

$outcome = $transport->run(function (FileTransportResult $result) use (&$contadores): void {
    $contadores[$result->status] = ($contadores[$result->status] ?? 0) + 1;

    $stream = in_array($result->status, ['error', 'conflict'], true) ? STDERR : STDOUT;
    fwrite($stream, "[{$result->status}] {$result->relativePath}\n");
});

if (!$outcome->connected) {
    fwrite(STDERR, "Falha ao conectar/autenticar no FTP de produção — nada foi processado.\n");

    exit(2);
}

fwrite(STDOUT, sprintf(
    "\nResumo: %d transportado(s), %d já atualizado(s), %d conflito(s), %d erro(s), %d diretório(s) protegido(s) garantido(s).\n",
    $contadores['transported'],
    $contadores['already_current'],
    $contadores['conflict'],
    $contadores['error'],
    $contadores['storage_dir_ensured'],
));

exit(($contadores['conflict'] > 0 || $contadores['error'] > 0) ? 1 : 0);

/**
 * Loader mínimo de .env (KEY=VALUE por linha, # para comentário) — o
 * psyche-ai não depende de nenhuma lib de dotenv (só getenv() em
 * produção, onde o front controller do investimentos369 já popula o
 * ambiente); este script de terminal é o único lugar do projeto que
 * precisa ler um .env local, então o loader fica só aqui, sem virar
 * dependência do projeto inteiro.
 */
function carregarDotenvLocal(string $caminho): void
{
    if (!is_file($caminho)) {
        return;
    }

    foreach (file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
        $linha = trim($linha);
        if ($linha === '' || str_starts_with($linha, '#') || !str_contains($linha, '=')) {
            continue;
        }

        [$chave, $valor] = explode('=', $linha, 2);
        $chave = trim($chave);

        if (getenv($chave) === false) {
            putenv($chave . '=' . trim($valor));
        }
    }
}
