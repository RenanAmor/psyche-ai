<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use DateTimeImmutable;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\Services\MemoriaApplicationService;
use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteMemoriaRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSessaoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class MemoriaApplicationServiceTest extends SQLiteTestCase
{
    private MemoriaApplicationService $service;
    private SQLiteSujeitoRepository $sujeitoRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sujeitoRepository = new SQLiteSujeitoRepository($this->pdo);
        $sessaoRepository = new SQLiteSessaoRepository($this->pdo);
        $this->service = new MemoriaApplicationService(new SQLiteMemoriaRepository($this->pdo));

        (new SujeitoApplicationService($this->sujeitoRepository))->criar('sujeito-1', 'Sujeito Um');

        $sessoes = new SessaoApplicationService($sessaoRepository, $this->sujeitoRepository);
        $sessoes->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));
        $sessoes->criar('sujeito-1', 'sessao-2', new DateTimeImmutable('2026-01-20 10:00:00'));
    }

    private function sujeitoComSessoesCarregadas(): Sujeito
    {
        return $this->sujeitoRepository->findById('sujeito-1');
    }

    public function testConstroiEPersisteUmaMemoriaLongitudinal(): void
    {
        $dto = $this->service->criar($this->sujeitoComSessoesCarregadas(), 'memoria-1');

        self::assertSame('memoria-1', $dto->id);
        self::assertSame(2, $dto->quantidadeDeSessoes);

        $recuperada = $this->service->buscarPorId('memoria-1');
        self::assertNotNull($recuperada);
        self::assertSame(2, $recuperada->quantidadeDeSessoes);
    }

    public function testAtualizarReconstroiOPivoDeSessoes(): void
    {
        $this->service->criar($this->sujeitoComSessoesCarregadas(), 'memoria-1');

        $sujeito = $this->sujeitoComSessoesCarregadas();
        $sujeitoComUmaSessao = new Sujeito($sujeito->id(), $sujeito->nome());
        $sujeitoComUmaSessao->adicionarSessao($sujeito->sessoes()[0]);

        $atualizada = $this->service->atualizar($sujeitoComUmaSessao, 'memoria-1');

        self::assertSame(1, $atualizada->quantidadeDeSessoes);
        self::assertSame(1, $this->service->buscarPorId('memoria-1')->quantidadeDeSessoes);
    }

    public function testAtualizarLancaExcecaoQuandoMemoriaNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->atualizar($this->sujeitoComSessoesCarregadas(), 'memoria-inexistente');
    }

    public function testExcluiUmaMemoria(): void
    {
        $this->service->criar($this->sujeitoComSessoesCarregadas(), 'memoria-1');

        $this->service->excluir('memoria-1');

        self::assertNull($this->service->buscarPorId('memoria-1'));
    }

    public function testExcluirLancaExcecaoQuandoMemoriaNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->excluir('memoria-inexistente');
    }

    public function testListaTodasAsMemorias(): void
    {
        $this->service->criar($this->sujeitoComSessoesCarregadas(), 'memoria-1');
        $this->service->criar($this->sujeitoComSessoesCarregadas(), 'memoria-2');

        self::assertCount(2, $this->service->listar());
    }
}
