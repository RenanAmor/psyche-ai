<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use PsycheAI\Application\Services\ListaDeEsperaApplicationService;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteListaDeEsperaRepository;
use PsycheAI\Infrastructure\UUID\RandomUuidGenerator;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class ListaDeEsperaApplicationServiceTest extends SQLiteTestCase
{
    private ListaDeEsperaApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ListaDeEsperaApplicationService(
            new SQLiteListaDeEsperaRepository($this->pdo),
            new RandomUuidGenerator()
        );
    }

    private function inscrever(string $email = 'interessado@psyche.ai'): \PsycheAI\Application\DTOs\InscricaoListaDeEsperaDTO
    {
        return $this->service->inscrever(
            $email,
            'Ana Interessada',
            'Psicóloga',
            'Universidade Federal',
            'Brasil/SP',
            'Quero participar da pesquisa.',
            true,
            true
        );
    }

    public function testInscreveEPersisteComIdGerado(): void
    {
        $dto = $this->inscrever();

        $this->assertNotSame('', $dto->id);
        $this->assertSame('interessado@psyche.ai', $dto->email);
        $this->assertSame('Ana Interessada', $dto->nome);
    }

    public function testInscreverComEmailJaExistenteNaoDuplicaERetornaAInscricaoOriginal(): void
    {
        $primeira = $this->inscrever();
        $segunda = $this->inscrever();

        $this->assertSame($primeira->id, $segunda->id);
        $this->assertCount(1, $this->service->listar());
    }

    public function testListarRetornaVazioPorPadrao(): void
    {
        $this->assertSame([], $this->service->listar());
    }

    public function testListarRetornaTodasAsInscricoes(): void
    {
        $this->inscrever('um@psyche.ai');
        $this->inscrever('dois@psyche.ai');

        $this->assertCount(2, $this->service->listar());
    }
}
