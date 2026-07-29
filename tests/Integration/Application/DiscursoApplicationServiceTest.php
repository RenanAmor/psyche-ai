<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use DateTimeImmutable;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\Services\DiscursoApplicationService;
use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteDiscursoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSessaoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class DiscursoApplicationServiceTest extends SQLiteTestCase
{
    private DiscursoApplicationService $service;
    private SQLiteSessaoRepository $sessaoRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $sujeitoRepository = new SQLiteSujeitoRepository($this->pdo);
        $this->sessaoRepository = new SQLiteSessaoRepository($this->pdo);
        $this->service = new DiscursoApplicationService(
            new SQLiteDiscursoRepository($this->pdo),
            $this->sessaoRepository
        );

        (new SujeitoApplicationService($sujeitoRepository))->criar('sujeito-1', 'Sujeito Um');
        (new SessaoApplicationService($this->sessaoRepository, $sujeitoRepository))
            ->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));
    }

    public function testCriaUmDiscursoVinculadoASessao(): void
    {
        $dto = $this->service->criar('sessao-1', 'discurso-1', 'Conteúdo do discurso');

        self::assertSame('discurso-1', $dto->id);
        self::assertSame(0, $dto->quantidadeDeEventos);

        $sessaoRecuperada = $this->sessaoRepository->findById('sessao-1');
        self::assertCount(1, $sessaoRecuperada->discursos());
    }

    public function testCriarLancaExcecaoQuandoSessaoNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->criar('sessao-inexistente', 'discurso-1', 'Conteúdo');
    }

    public function testAtualizaOConteudoPreservandoOsEventosExistentes(): void
    {
        $this->service->criar('sessao-1', 'discurso-1', 'Conteúdo original');
        $this->service->adicionarEvento('discurso-1', 'evento-1', 'Lapso', 3);

        $atualizado = $this->service->atualizar('discurso-1', 'Conteúdo atualizado');

        self::assertSame('Conteúdo atualizado', $atualizado->conteudo);
        self::assertSame(1, $atualizado->quantidadeDeEventos);
    }

    public function testAdicionarEventoCascateiaParaOAgregadoSessao(): void
    {
        $this->service->criar('sessao-1', 'discurso-1', 'Conteúdo');

        $dto = $this->service->adicionarEvento('discurso-1', 'evento-1', 'Lapso', 3);

        self::assertSame(1, $dto->quantidadeDeEventos);

        $sessaoRecuperada = $this->sessaoRepository->findById('sessao-1');
        $discursoRecuperado = $sessaoRecuperada->discursos()[0];
        self::assertCount(1, $discursoRecuperado->eventos());
        self::assertSame('Lapso', $discursoRecuperado->eventos()[0]->conteudo()->valor());
    }

    public function testExcluiUmDiscurso(): void
    {
        $this->service->criar('sessao-1', 'discurso-1', 'Conteúdo');

        $this->service->excluir('discurso-1');

        self::assertNull($this->service->buscarPorId('discurso-1'));
    }

    public function testBuscarPorIdRetornaNuloQuandoNaoEncontrado(): void
    {
        self::assertNull($this->service->buscarPorId('inexistente'));
    }

    public function testListaTodosOsDiscursos(): void
    {
        $this->service->criar('sessao-1', 'discurso-1', 'Conteúdo 1');
        $this->service->criar('sessao-1', 'discurso-2', 'Conteúdo 2');

        self::assertCount(2, $this->service->listar());
    }

    public function testListarEventosRetornaTodosComODiscursoIdAssociado(): void
    {
        $this->service->criar('sessao-1', 'discurso-1', 'Conteúdo');
        $this->service->adicionarEvento('discurso-1', 'evento-1', 'Lapso', 3);
        $this->service->adicionarEvento('discurso-1', 'evento-2', 'Chiste', 7);

        $eventos = $this->service->listarEventos();

        self::assertCount(2, $eventos);
        self::assertSame('discurso-1', $eventos[0]->discursoId);
    }

    public function testBuscarEventoRetornaNuloQuandoNaoEncontrado(): void
    {
        self::assertNull($this->service->buscarEvento('inexistente'));
    }

    public function testBuscarEventoRetornaODtoComODiscursoId(): void
    {
        $this->service->criar('sessao-1', 'discurso-1', 'Conteúdo');
        $this->service->adicionarEvento('discurso-1', 'evento-1', 'Lapso', 3);

        $encontrado = $this->service->buscarEvento('evento-1');

        self::assertNotNull($encontrado);
        self::assertSame('discurso-1', $encontrado->discursoId);
        self::assertSame('Lapso', $encontrado->conteudo);
    }

    public function testAtualizarEventoLancaExcecaoQuandoNaoEncontrado(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->atualizarEvento('inexistente', 'Novo conteúdo', 1);
    }

    public function testAtualizarEventoPreservaOsDemaisEventosDoDiscurso(): void
    {
        $this->service->criar('sessao-1', 'discurso-1', 'Conteúdo');
        $this->service->adicionarEvento('discurso-1', 'evento-1', 'Lapso', 3);
        $this->service->adicionarEvento('discurso-1', 'evento-2', 'Chiste', 7);

        $atualizado = $this->service->atualizarEvento('evento-1', 'Lapso revisado', 5);

        self::assertSame('Lapso revisado', $atualizado->conteudo);
        self::assertSame(5, $atualizado->posicao);

        $discursoRecuperado = $this->service->buscarPorId('discurso-1');
        self::assertSame(2, $discursoRecuperado->quantidadeDeEventos);
    }

    public function testRemoverEventoLancaExcecaoQuandoNaoEncontrado(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->removerEvento('inexistente');
    }

    public function testRemoverEventoExcluiApenasOEventoInformado(): void
    {
        $this->service->criar('sessao-1', 'discurso-1', 'Conteúdo');
        $this->service->adicionarEvento('discurso-1', 'evento-1', 'Lapso', 3);
        $this->service->adicionarEvento('discurso-1', 'evento-2', 'Chiste', 7);

        $this->service->removerEvento('evento-1');

        self::assertNull($this->service->buscarEvento('evento-1'));
        self::assertNotNull($this->service->buscarEvento('evento-2'));
        self::assertSame(1, $this->service->buscarPorId('discurso-1')->quantidadeDeEventos);
    }
}
