<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Support;

use PsycheAI\Infrastructure\Contracts\StorageInterface;
use RuntimeException;

/**
 * Duplo de teste de StorageInterface: mantém os "arquivos" em memória —
 * evita que testes de Application/Feature gravem áudio de verdade em
 * disco (LocalFilesystemStorage é reservada à verificação de ponta a
 * ponta e ao uso real, ver LocalFilesystemStorageTest).
 */
final class StorageStub implements StorageInterface
{
    /** @var array<string, string> */
    private array $arquivos = [];

    public function put(string $path, string $contents): void
    {
        $this->arquivos[$path] = $contents;
    }

    public function get(string $path): string
    {
        return $this->arquivos[$path] ?? throw new RuntimeException("Arquivo não encontrado: {$path}");
    }

    public function exists(string $path): bool
    {
        return isset($this->arquivos[$path]);
    }

    public function delete(string $path): void
    {
        unset($this->arquivos[$path]);
    }

    public function contemAlgumArquivo(): bool
    {
        return $this->arquivos !== [];
    }
}
