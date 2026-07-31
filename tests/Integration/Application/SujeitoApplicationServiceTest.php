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

    public function testRegistrarContaLigaEmailESenhaPreservandoSessoesExistentes(): void
    {
        $this->service->criar('sujeito-1', 'Visitante');

        $dto = $this->service->registrarConta('sujeito-1', 'sujeito@psyche.ai', 'segredo');

        self::assertSame('sujeito@psyche.ai', $dto->email);
        self::assertSame('sujeito@psyche.ai', $this->service->buscarPorId('sujeito-1')->email);
    }

    public function testRegistrarContaLancaExcecaoQuandoSujeitoNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->registrarConta('inexistente', 'sujeito@psyche.ai', 'segredo');
    }

    public function testBuscarPorEmailRetornaNuloQuandoNaoEncontrado(): void
    {
        self::assertNull($this->service->buscarPorEmail('inexistente@psyche.ai'));
    }

    public function testBuscarPorEmailEncontraOSujeitoComConta(): void
    {
        $this->service->criar('sujeito-1', 'Visitante');
        $this->service->registrarConta('sujeito-1', 'sujeito@psyche.ai', 'segredo');

        $encontrado = $this->service->buscarPorEmail('sujeito@psyche.ai');

        self::assertNotNull($encontrado);
        self::assertSame('sujeito-1', $encontrado->id);
    }

    public function testAutenticarComCredenciaisCorretasRetornaODTO(): void
    {
        $this->service->criar('sujeito-1', 'Visitante');
        $this->service->registrarConta('sujeito-1', 'sujeito@psyche.ai', 'segredo');

        $autenticado = $this->service->autenticar('sujeito@psyche.ai', 'segredo');

        self::assertNotNull($autenticado);
        self::assertSame('sujeito-1', $autenticado->id);
    }

    public function testAutenticarComSenhaIncorretaRetornaNulo(): void
    {
        $this->service->criar('sujeito-1', 'Visitante');
        $this->service->registrarConta('sujeito-1', 'sujeito@psyche.ai', 'segredo');

        self::assertNull($this->service->autenticar('sujeito@psyche.ai', 'senha-errada'));
    }

    public function testAutenticarComEmailInexistenteRetornaNulo(): void
    {
        self::assertNull($this->service->autenticar('inexistente@psyche.ai', 'segredo'));
    }
}
