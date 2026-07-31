<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Storage\LocalFilesystemStorage;
use RuntimeException;

final class LocalFilesystemStorageTest extends TestCase
{
    private string $raizTemporaria;

    protected function setUp(): void
    {
        $this->raizTemporaria = sys_get_temp_dir() . '/psyche-ai-storage-test-' . bin2hex(random_bytes(4));
    }

    protected function tearDown(): void
    {
        if (!is_dir($this->raizTemporaria)) {
            return;
        }

        foreach (glob($this->raizTemporaria . '/*/*') ?: [] as $arquivo) {
            unlink($arquivo);
        }
        foreach (glob($this->raizTemporaria . '/*') ?: [] as $subdiretorio) {
            @rmdir($subdiretorio);
        }
        rmdir($this->raizTemporaria);
    }

    public function testPutGetExistsEDelete(): void
    {
        $storage = new LocalFilesystemStorage($this->raizTemporaria);

        $storage->put('sessoes/1.webm', 'conteudo-binario');

        $this->assertTrue($storage->exists('sessoes/1.webm'));
        $this->assertSame('conteudo-binario', $storage->get('sessoes/1.webm'));

        $storage->delete('sessoes/1.webm');

        $this->assertFalse($storage->exists('sessoes/1.webm'));
    }

    public function testGetLancaExcecaoQuandoArquivoNaoExiste(): void
    {
        $storage = new LocalFilesystemStorage($this->raizTemporaria);

        $this->expectException(RuntimeException::class);

        $storage->get('inexistente.webm');
    }

    public function testExistsRetornaFalsoParaArquivoInexistente(): void
    {
        $storage = new LocalFilesystemStorage($this->raizTemporaria);

        $this->assertFalse($storage->exists('inexistente.webm'));
    }
}
