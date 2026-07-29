<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

/**
 * Porta de armazenamento de arquivos/blobs brutos (ex.: áudios de sessão),
 * distinta de `PersistenceInterface`, que trata de registros estruturados.
 */
interface StorageInterface
{
    public function put(string $path, string $contents): void;

    public function get(string $path): string;

    public function exists(string $path): bool;

    public function delete(string $path): void;
}
