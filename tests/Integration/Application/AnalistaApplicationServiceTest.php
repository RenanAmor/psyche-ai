<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use PsycheAI\Application\Services\AnalistaApplicationService;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteAnalistaRepository;
use PsycheAI\Infrastructure\UUID\RandomUuidGenerator;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class AnalistaApplicationServiceTest extends SQLiteTestCase
{
    private AnalistaApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AnalistaApplicationService(
            new SQLiteAnalistaRepository($this->pdo),
            new RandomUuidGenerator()
        );
    }

    public function testCriaEPersisteUmAnalistaComIdGerado(): void
    {
        $dto = $this->service->criar('analista@psyche.ai', 'segredo');

        $this->assertNotSame('', $dto->id);
        $this->assertSame('analista@psyche.ai', $dto->email);
    }

    public function testBuscarPorEmailRetornaNuloQuandoNaoEncontrado(): void
    {
        $this->assertNull($this->service->buscarPorEmail('inexistente@psyche.ai'));
    }

    public function testBuscarPorEmailEncontraOAnalistaCriado(): void
    {
        $this->service->criar('analista@psyche.ai', 'segredo');

        $encontrado = $this->service->buscarPorEmail('analista@psyche.ai');

        $this->assertNotNull($encontrado);
        $this->assertSame('analista@psyche.ai', $encontrado->email);
    }

    public function testAutenticarComCredenciaisCorretasRetornaODTO(): void
    {
        $this->service->criar('analista@psyche.ai', 'segredo');

        $autenticado = $this->service->autenticar('analista@psyche.ai', 'segredo');

        $this->assertNotNull($autenticado);
        $this->assertSame('analista@psyche.ai', $autenticado->email);
    }

    public function testAutenticarComSenhaIncorretaRetornaNulo(): void
    {
        $this->service->criar('analista@psyche.ai', 'segredo');

        $this->assertNull($this->service->autenticar('analista@psyche.ai', 'senha-errada'));
    }

    public function testAutenticarComEmailInexistenteRetornaNulo(): void
    {
        $this->assertNull($this->service->autenticar('inexistente@psyche.ai', 'segredo'));
    }
}
