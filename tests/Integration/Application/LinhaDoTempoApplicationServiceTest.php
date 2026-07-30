<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use DateTimeImmutable;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\Services\DiscursoApplicationService;
use PsycheAI\Application\Services\LinhaDoTempoApplicationService;
use PsycheAI\Application\Services\MemoriaApplicationService;
use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteDiscursoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteMemoriaRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSessaoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class LinhaDoTempoApplicationServiceTest extends SQLiteTestCase
{
    private LinhaDoTempoApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $sujeitoRepository = new SQLiteSujeitoRepository($this->pdo);
        $sessaoRepository = new SQLiteSessaoRepository($this->pdo);
        $discursoRepository = new SQLiteDiscursoRepository($this->pdo);
        $memoriaRepository = new SQLiteMemoriaRepository($this->pdo);

        $this->service = new LinhaDoTempoApplicationService($sujeitoRepository, $memoriaRepository, $sessaoRepository);

        $sujeitos = new SujeitoApplicationService($sujeitoRepository);
        $sessoes = new SessaoApplicationService($sessaoRepository, $sujeitoRepository);
        $discursos = new DiscursoApplicationService($discursoRepository, $sessaoRepository);
        $memorias = new MemoriaApplicationService($memoriaRepository);

        $sujeitos->criar('sujeito-1', 'Sujeito Um');
        $sessoes->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));
        $sessoes->criar('sujeito-1', 'sessao-2', new DateTimeImmutable('2026-01-20 10:00:00'));
        $discursos->criar('sessao-1', 'discurso-1', 'Conteúdo A');
        $discursos->adicionarEvento('discurso-1', 'evento-1', 'Lapso', 0);
        $memorias->criar($sujeitoRepository->findById('sujeito-1'), 'memoria-1');

        // Segundo Sujeito, para garantir que a Linha do Tempo de um não
        // vaza itens do outro.
        $sujeitos->criar('sujeito-2', 'Sujeito Dois');
        $sessoes->criar('sujeito-2', 'sessao-x', new DateTimeImmutable('2026-01-15 10:00:00'));
        $memorias->criar($sujeitoRepository->findById('sujeito-2'), 'memoria-x');
    }

    public function testConsultarLancaExcecaoQuandoSujeitoNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->consultar('inexistente', null, null, null, null, 1, 20);
    }

    public function testConsultarDevolveTodosOsTiposOrdenadosCronologicamenteApenasDoSujeito(): void
    {
        $resultado = $this->service->consultar('sujeito-1', null, null, null, null, 1, 20);

        self::assertSame(5, $resultado->total);
        self::assertSame(
            ['sessao-1', 'discurso-1', 'sessao-2', 'memoria-1', 'evento-1'],
            array_map(static fn ($item) => $item->id, $resultado->itens)
        );
        self::assertSame(
            ['sessao', 'discurso', 'sessao', 'memoria', 'evento'],
            array_map(static fn ($item) => $item->tipo, $resultado->itens)
        );
    }

    public function testConsultarFiltraPorTipo(): void
    {
        $resultado = $this->service->consultar('sujeito-1', 'evento', null, null, null, 1, 20);

        self::assertSame(1, $resultado->total);
        self::assertSame('evento-1', $resultado->itens[0]->id);
    }

    public function testConsultarFiltraPorIntervaloDeData(): void
    {
        $resultado = $this->service->consultar(
            'sujeito-1',
            null,
            new DateTimeImmutable('2026-01-15 00:00:00'),
            new DateTimeImmutable('2026-01-25 00:00:00'),
            null,
            1,
            20
        );

        self::assertSame(2, $resultado->total);
        self::assertSame(['sessao-2', 'memoria-1'], array_map(static fn ($item) => $item->id, $resultado->itens));
    }

    public function testConsultarFiltraPorTexto(): void
    {
        $resultado = $this->service->consultar('sujeito-1', null, null, null, 'lapso', 1, 20);

        self::assertSame(1, $resultado->total);
        self::assertSame('evento-1', $resultado->itens[0]->id);
    }

    public function testConsultarPagina(): void
    {
        $resultado = $this->service->consultar('sujeito-1', null, null, null, null, 2, 2);

        self::assertSame(5, $resultado->total);
        self::assertSame(3, $resultado->totalDePaginas);
        self::assertSame(2, $resultado->pagina);
        self::assertSame(['sessao-2', 'memoria-1'], array_map(static fn ($item) => $item->id, $resultado->itens));
    }
}
