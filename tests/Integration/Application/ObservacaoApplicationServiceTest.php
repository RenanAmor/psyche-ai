<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Application;

use DateTimeImmutable;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\Services\DiscursoApplicationService;
use PsycheAI\Application\Services\ObservacaoApplicationService;
use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Application\UseCases\ClassificarFormacaoFreudiana\ClassificarFormacaoFreudianaHandler;
use PsycheAI\Domain\ValueObjects\TipoFormacaoFreudiana;
use PsycheAI\Infrastructure\Contracts\ClassificadorEstruturalInterface;
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

        // Terceiro Sujeito: recorrência dentro de uma única Sessão (não
        // atravessa Sessões distintas) — usado para testar o ramo 2 da
        // regra de precedência (classificação via Motor Freud/LLM), que
        // só se aplica quando não há circuito.
        $sujeitos->criar('sujeito-3', 'Sujeito Três');
        $sessoes->criar('sujeito-3', 'sessao-unica', new DateTimeImmutable('2026-01-10 10:00:00'));
        $discursos->criar('sessao-unica', 'discurso-3', 'Conteúdo C');
        $discursos->adicionarEvento('discurso-3', 'evento-4', 'trocadilho', 0);
        $discursos->adicionarEvento('discurso-3', 'evento-5', 'trocadilho', 1);
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

    public function testConsultarCircuitoLancaExcecaoQuandoSujeitoNaoExiste(): void
    {
        $this->expectException(RecursoNaoEncontradoException::class);

        $this->service->consultarCircuito('inexistente');
    }

    public function testConsultarCircuitoDevolveOTrajetoDaRecorrenciaAtravesDasSessoes(): void
    {
        $resultado = $this->service->consultarCircuito('sujeito-1');

        self::assertSame('sujeito-1', $resultado->sujeitoId);
        self::assertCount(1, $resultado->circuitos);

        $circuito = $resultado->circuitos[0];
        self::assertSame('lapso', $circuito->descricao);
        self::assertSame(2, $circuito->frequencia);
        self::assertCount(2, $circuito->ocorrencias);
        self::assertSame('sessao-1', $circuito->ocorrencias[0]->sessaoId);
        self::assertSame('sessao-2', $circuito->ocorrencias[1]->sessaoId);
    }

    public function testConsultarCircuitoNaoVazaEntreSujeitos(): void
    {
        $resultado = $this->service->consultarCircuito('sujeito-2');

        self::assertSame([], $resultado->circuitos);
    }

    public function testConsultarCircuitoSemLeituraLacanianaDevolveRotuloNulo(): void
    {
        $resultado = $this->service->consultarCircuito('sujeito-1');

        self::assertNull($resultado->circuitos[0]->rotuloLacaniano);
    }

    public function testConsultarCircuitoComLeituraLacanianaRotulaComoCircuitoQuandoAtravessaSessoes(): void
    {
        $resultado = $this->service->consultarCircuito('sujeito-1', null, true);

        self::assertSame(
            'Estrutura candidata: circuito — o tema retorna ao mesmo ponto através de sessões distintas.',
            $resultado->circuitos[0]->rotuloLacaniano
        );
    }

    public function testConsultarCircuitoSemLeituraLacanianaDevolveFundamentacaoNula(): void
    {
        $resultado = $this->service->consultarCircuito('sujeito-1');

        self::assertNull($resultado->circuitos[0]->fundamentacaoTeorica);
    }

    public function testConsultarCircuitoComLeituraLacanianaIncluiFundamentacaoDoCircuito(): void
    {
        $resultado = $this->service->consultarCircuito('sujeito-1', null, true);

        self::assertStringContainsString('Circuito', $resultado->circuitos[0]->fundamentacaoTeorica);
    }

    public function testConsultarCircuitoSemCircuitoESemClassificadorCaiNoRotuloPadrao(): void
    {
        $resultado = $this->service->consultarCircuito('sujeito-3', null, true);

        self::assertSame(
            'Estrutura candidata: deslize metonímico.',
            $resultado->circuitos[0]->rotuloLacaniano
        );
        self::assertStringContainsString('Deslize metonímico', $resultado->circuitos[0]->fundamentacaoTeorica);
    }

    public function testConsultarCircuitoSemCircuitoUsaClassificadorFreudianoQuandoDisponivel(): void
    {
        $classificador = new class implements ClassificadorEstruturalInterface {
            public function classificar(string $conteudo): TipoFormacaoFreudiana
            {
                return TipoFormacaoFreudiana::Chiste;
            }
        };

        $sujeitoRepository = new SQLiteSujeitoRepository($this->pdo);
        $service = new ObservacaoApplicationService(
            sujeitoRepository: $sujeitoRepository,
            classificarFormacaoFreudiana: new ClassificarFormacaoFreudianaHandler($classificador)
        );

        $resultado = $service->consultarCircuito('sujeito-3', null, true);

        self::assertSame(
            'Estrutura candidata: metáfora — condensação.',
            $resultado->circuitos[0]->rotuloLacaniano
        );
        self::assertStringContainsString('Metáfora', $resultado->circuitos[0]->fundamentacaoTeorica);
    }

    public function testConsultarCircuitoNaoChamaOClassificadorQuandoJaHaCircuito(): void
    {
        $classificador = new class implements ClassificadorEstruturalInterface {
            public bool $chamado = false;

            public function classificar(string $conteudo): TipoFormacaoFreudiana
            {
                $this->chamado = true;

                return TipoFormacaoFreudiana::Chiste;
            }
        };

        $sujeitoRepository = new SQLiteSujeitoRepository($this->pdo);
        $service = new ObservacaoApplicationService(
            sujeitoRepository: $sujeitoRepository,
            classificarFormacaoFreudiana: new ClassificarFormacaoFreudianaHandler($classificador)
        );

        $resultado = $service->consultarCircuito('sujeito-1', null, true);

        self::assertFalse($classificador->chamado);
        self::assertSame(
            'Estrutura candidata: circuito — o tema retorna ao mesmo ponto através de sessões distintas.',
            $resultado->circuitos[0]->rotuloLacaniano
        );
    }
}
