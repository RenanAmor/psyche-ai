<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use PsycheAI\Application\Services\ParticipanteApplicationService;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteParticipanteRepository;
use PsycheAI\Infrastructure\UUID\RandomUuidGenerator;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class ParticipanteApplicationServiceTest extends SQLiteTestCase
{
    private ParticipanteApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ParticipanteApplicationService(
            new SQLiteParticipanteRepository($this->pdo),
            new RandomUuidGenerator()
        );
    }

    public function testCriaEPersisteUmParticipanteComIdGerado(): void
    {
        $dto = $this->service->criar('participante@psyche.ai', 'segredo');

        $this->assertNotSame('', $dto->id);
        $this->assertSame('participante@psyche.ai', $dto->email);
    }

    public function testBuscarPorEmailRetornaNuloQuandoNaoEncontrado(): void
    {
        $this->assertNull($this->service->buscarPorEmail('inexistente@psyche.ai'));
    }

    public function testBuscarPorEmailEncontraOParticipanteCriado(): void
    {
        $this->service->criar('participante@psyche.ai', 'segredo');

        $encontrado = $this->service->buscarPorEmail('participante@psyche.ai');

        $this->assertNotNull($encontrado);
        $this->assertSame('participante@psyche.ai', $encontrado->email);
    }

    public function testAutenticarComCredenciaisCorretasRetornaODTO(): void
    {
        $this->service->criar('participante@psyche.ai', 'segredo');

        $autenticado = $this->service->autenticar('participante@psyche.ai', 'segredo');

        $this->assertNotNull($autenticado);
        $this->assertSame('participante@psyche.ai', $autenticado->email);
    }

    public function testAutenticarComSenhaIncorretaRetornaNulo(): void
    {
        $this->service->criar('participante@psyche.ai', 'segredo');

        $this->assertNull($this->service->autenticar('participante@psyche.ai', 'senha-errada'));
    }

    public function testAutenticarComEmailInexistenteRetornaNulo(): void
    {
        $this->assertNull($this->service->autenticar('inexistente@psyche.ai', 'segredo'));
    }
}
