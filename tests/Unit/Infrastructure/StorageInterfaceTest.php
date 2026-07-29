<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Contracts\StorageInterface;
use RuntimeException;

final class StorageInterfaceTest extends TestCase
{
    public function testImplementationPutsGetsAndDeletesFiles(): void
    {
        $storage = new class implements StorageInterface {
            /** @var array<string, string> */
            private array $files = [];

            public function put(string $path, string $contents): void
            {
                $this->files[$path] = $contents;
            }

            public function get(string $path): string
            {
                if (!$this->exists($path)) {
                    throw new RuntimeException("Arquivo não encontrado: {$path}");
                }

                return $this->files[$path];
            }

            public function exists(string $path): bool
            {
                return isset($this->files[$path]);
            }

            public function delete(string $path): void
            {
                unset($this->files[$path]);
            }
        };

        $storage->put('sessoes/1.wav', 'conteudo-binario');

        $this->assertTrue($storage->exists('sessoes/1.wav'));
        $this->assertSame('conteudo-binario', $storage->get('sessoes/1.wav'));

        $storage->delete('sessoes/1.wav');

        $this->assertFalse($storage->exists('sessoes/1.wav'));
    }
}
