<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use DateTimeImmutable;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSessaoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class SessaoApplicationServiceTest extends SQLiteTestCase
{
    private SessaoApplicationService $service;
    private SujeitoApplicationService $sujeitos;
    private SQLiteSujeitoRepository $sujeitoRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sujeitoRepository = new SQLiteSujeitoRepository($this->pdo);
        $this->sujeitos = new SujeitoApplicationService($this->sujeitoRepository);
        $this->service = new SessaoApplicationService(
            new SQLiteSessaoRepository($this->pdo),
            $this->sujeitoRepository
        );

        $this->sujeitos->criar('sujeito-1', 'Sujeito Um');
    }

    public function testCriaUmaSessaoVinculadaAoSujeito(): void
    {
        $dto = $this->service->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));

        self::assertSame('sessao-1', $dto->id);
        self::assertSame(0, $dto->quantidadeDeDiscursos);

        $sujeitoRecuperado = $this->sujeitoRepository->findById('sujeito-1');
        self::assertCount(1, $sujeitoRecuperado->sessoes());
        self::assertSame('sessao-1', $sujeitoRecuperado->sessoes()[0]->id()->valor());
    }

    public function testCriarLancaExcecaoQuandoSujeitoNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->criar('sujeito-inexistente', 'sessao-1', new DateTimeImmutable());
    }

    public function testAtualizaADataPreservandoOsDiscursosExistentes(): void
    {
        $this->service->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));

        $atualizada = $this->service->atualizar('sessao-1', new DateTimeImmutable('2026-02-20 15:30:00'));

        self::assertStringStartsWith('2026-02-20', $atualizada->data);

        $sujeitoRecuperado = $this->sujeitoRepository->findById('sujeito-1');
        self::assertCount(1, $sujeitoRecuperado->sessoes(), 'a sessão deve continuar vinculada ao sujeito após a atualização');
    }

    public function testAtualizarLancaExcecaoQuandoSessaoNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->atualizar('inexistente', new DateTimeImmutable());
    }

    public function testExcluiUmaSessao(): void
    {
        $this->service->criar('sujeito-1', 'sessao-1', new DateTimeImmutable());

        $this->service->excluir('sessao-1');

        self::assertNull($this->service->buscarPorId('sessao-1'));
    }

    public function testBuscarPorIdRetornaNuloQuandoNaoEncontrado(): void
    {
        self::assertNull($this->service->buscarPorId('inexistente'));
    }

    public function testListaTodasAsSessoes(): void
    {
        $this->service->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-01 00:00:00'));
        $this->service->criar('sujeito-1', 'sessao-2', new DateTimeImmutable('2026-01-02 00:00:00'));

        self::assertCount(2, $this->service->listar());
    }
}
