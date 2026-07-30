<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use DateTimeImmutable;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\Services\DiscursoApplicationService;
use PsycheAI\Application\Services\ObservacaoApplicationService;
use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteDiscursoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSessaoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class ObservacaoApplicationServiceTest extends SQLiteTestCase
{
    private ObservacaoApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $sujeitoRepository = new SQLiteSujeitoRepository($this->pdo);
        $sessaoRepository = new SQLiteSessaoRepository($this->pdo);
        $discursoRepository = new SQLiteDiscursoRepository($this->pdo);

        $this->service = new ObservacaoApplicationService($sujeitoRepository);

        $sujeitos = new SujeitoApplicationService($sujeitoRepository);
        $sessoes = new SessaoApplicationService($sessaoRepository, $sujeitoRepository);
        $discursos = new DiscursoApplicationService($discursoRepository, $sessaoRepository);

        $sujeitos->criar('sujeito-1', 'Sujeito Um');
        $sessoes->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));
        $sessoes->criar('sujeito-1', 'sessao-2', new DateTimeImmutable('2026-01-20 10:00:00'));
        $discursos->criar('sessao-1', 'discurso-1', 'Conteúdo A');
        $discursos->adicionarEvento('discurso-1', 'evento-1', 'lapso', 0);
        $discursos->criar('sessao-2', 'discurso-2', 'Conteúdo B');
        $discursos->adicionarEvento('discurso-2', 'evento-2', 'lapso', 0);
        $discursos->adicionarEvento('discurso-2', 'evento-3', 'chiste', 1);

        // Segundo Sujeito, para garantir que as Observações de um não
        // vazam recorrências do outro.
        $sujeitos->criar('sujeito-2', 'Sujeito Dois');
        $sessoes->criar('sujeito-2', 'sessao-x', new DateTimeImmutable('2026-01-15 10:00:00'));
    }

    public function testConsultarLancaExcecaoQuandoSujeitoNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->consultar('inexistente');
    }

    public function testConsultarDevolveAsRecorrenciasEObservacoesDoSujeito(): void
    {
        $resultado = $this->service->consultar('sujeito-1');

        self::assertSame('sujeito-1', $resultado->sujeitoId);
        self::assertCount(1, $resultado->recorrencias);
        self::assertSame('lapso', $resultado->recorrencias[0]->descricao);
        self::assertSame(2, $resultado->recorrencias[0]->frequencia);

        self::assertCount(1, $resultado->observacoes);
        self::assertSame(
            'Recorrência observada: lapso (2 ocorrência(s)).',
            $resultado->observacoes[0]->texto
        );
    }

    public function testConsultarNaoVazaRecorrenciasEntreSujeitos(): void
    {
        $resultado = $this->service->consultar('sujeito-2');

        self::assertSame([], $resultado->recorrencias);
        self::assertSame([], $resultado->observacoes);
    }

    public function testConsultarAceitaMinimoDeRecorrenciaPersonalizado(): void
    {
        $resultado = $this->service->consultar('sujeito-1', 1);

        $descricoes = array_map(static fn ($recorrencia) => $recorrencia->descricao, $resultado->recorrencias);
        sort($descricoes);

        self::assertSame(['chiste', 'lapso'], $descricoes);
    }

    public function testConsultarSemLeituraLacanianaDevolveRotuloNulo(): void
    {
        $resultado = $this->service->consultar('sujeito-1');

        self::assertNull($resultado->recorrencias[0]->rotuloLacaniano);
    }

    public function testConsultarComLeituraLacanianaRotulaAsRecorrenciasDoFreud(): void
    {
        $resultado = $this->service->consultar('sujeito-1', null, true);

        self::assertCount(1, $resultado->recorrencias);
        self::assertSame('lapso', $resultado->recorrencias[0]->descricao);
        self::assertSame(
            'Estrutura candidata: deslize metonímico.',
            $resultado->recorrencias[0]->rotuloLacaniano
        );
    }
}
