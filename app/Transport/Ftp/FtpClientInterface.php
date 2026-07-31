<?php

declare(strict_types=1);

namespace PsycheAI\Transport\Ftp;

/**
 * Abstrai as operações FTP usadas pelo transporte de produção, permitindo
 * testar ProductionTransport sem rede real (injetando uma implementação fake).
 * Mesmo contrato usado pelo Collector369 (Sprint 14 daquele projeto).
 */
interface FtpClientInterface
{
    public function connect(string $host, int $port, int $timeoutSeconds): bool;

    public function login(string $user, string $password): bool;

    public function mkdir(string $path): bool;

    /**
     * Retorna o tamanho do arquivo remoto, ou -1 se não existir.
     */
    public function size(string $path): int;

    public function put(string $localPath, string $remotePath): bool;

    public function get(string $remotePath, string $localPath): bool;

    public function rename(string $from, string $to): bool;

    public function delete(string $path): bool;

    public function close(): void;
}
