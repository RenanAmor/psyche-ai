<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Storage;

use PsycheAI\Infrastructure\Contracts\StorageInterface;
use RuntimeException;

/**
 * Primeira implementação concreta de StorageInterface (porta definida
 * desde a Sprint 7, sem implementação até a Sprint 22): grava
 * blobs/arquivos brutos (áudios de sessão) no disco local, sob uma raiz
 * configurável — mesmo padrão de leitura de variável de ambiente com
 * fallback usado por AnthropicLLMService.
 */
final class LocalFilesystemStorage implements StorageInterface
{
    private readonly string $rootPath;

    public function __construct(?string $rootPath = null)
    {
        $raiz = $rootPath ?? (getenv('PSYCHEAI_AUDIO_STORAGE_PATH') ?: null) ?? (__DIR__ . '/../../../storage/audio');

        $this->rootPath = rtrim($raiz, '/\\');
    }

    public function put(string $path, string $contents): void
    {
        $caminhoCompleto = $this->caminhoCompleto($path);

        $diretorio = dirname($caminhoCompleto);
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0775, true);
        }

        file_put_contents($caminhoCompleto, $contents);
    }

    public function get(string $path): string
    {
        if (!$this->exists($path)) {
            throw new RuntimeException("Arquivo não encontrado: {$path}");
        }

        return file_get_contents($this->caminhoCompleto($path));
    }

    public function exists(string $path): bool
    {
        return is_file($this->caminhoCompleto($path));
    }

    public function delete(string $path): void
    {
        if ($this->exists($path)) {
            unlink($this->caminhoCompleto($path));
        }
    }

    private function caminhoCompleto(string $path): string
    {
        return $this->rootPath . '/' . ltrim($path, '/\\');
    }
}
