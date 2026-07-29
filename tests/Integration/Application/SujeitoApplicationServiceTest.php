<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class SujeitoApplicationServiceTest extends SQLiteTestCase
{
    private SujeitoApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new SujeitoApplicationService(new SQLiteSujeitoRepository($this->pdo));
    }

    public function testCriaEPersisteUmSujeito(): void
    {
        $dto = $this->service->criar('sujeito-1', 'Sujeito Um');

        self::assertSame('sujeito-1', $dto->id);
        self::assertSame('Sujeito Um', $dto->nome);
        self::assertSame(0, $dto->quantidadeDeSessoes);

        $recuperado = $this->service->buscarPorId('sujeito-1');

        self::assertNotNull($recuperado);
        self::assertSame('Sujeito Um', $recuperado->nome);
    }

    public function testBuscarPorIdRetornaNuloQuandoNaoEncontrado(): void
    {
        self::assertNull($this->service->buscarPorId('inexistente'));
    }

    public function testAtualizaONomePreservandoAsSessoesExistentes(): void
    {
        $this->service->criar('sujeito-1', 'Nome Original');

        $atualizado = $this->service->atualizar('sujeito-1', 'Nome Atualizado');

        self::assertSame('Nome Atualizado', $atualizado->nome);
        self::assertSame('Nome Atualizado', $this->service->buscarPorId('sujeito-1')->nome);
    }

    public function testAtualizarLancaExcecaoQuandoSujeitoNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->atualizar('inexistente', 'Novo Nome');
    }

    public function testExcluiUmSujeito(): void
    {
        $this->service->criar('sujeito-1', 'Sujeito Um');

        $this->service->excluir('sujeito-1');

        self::assertNull($this->service->buscarPorId('sujeito-1'));
    }

    public function testExcluirLancaExcecaoQuandoSujeitoNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->excluir('inexistente');
    }

    public function testListaTodosOsSujeitosCadastrados(): void
    {
        $this->service->criar('sujeito-1', 'Sujeito Um');
        $this->service->criar('sujeito-2', 'Sujeito Dois');

        $lista = $this->service->listar();

        self::assertCount(2, $lista);
        self::assertSame(['sujeito-1', 'sujeito-2'], array_map(static fn ($dto) => $dto->id, $lista));
    }
}
