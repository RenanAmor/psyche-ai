<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Services\DetectorRecorrencias;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;

final class DetectorRecorrenciasTest extends TestCase
{
    public function testContaOcorrenciasPorConteudo(): void
    {
        $eventos = [
            new EventoDiscursivo(new Identificador('e1'), new ConteudoDiscursivo('lapso'), new Posicao(0)),
            new EventoDiscursivo(new Identificador('e2'), new ConteudoDiscursivo('lapso'), new Posicao(1)),
            new EventoDiscursivo(new Identificador('e3'), new ConteudoDiscursivo('chiste'), new Posicao(2)),
        ];

        $recorrencias = (new DetectorRecorrencias())->detectar($eventos);

        $this->assertSame(['lapso' => 2, 'chiste' => 1], $recorrencias);
    }

    public function testNormalizaEspacosEMaiusculasAoComparar(): void
    {
        $eventos = [
            new EventoDiscursivo(new Identificador('e1'), new ConteudoDiscursivo('lapso'), new Posicao(0)),
            new EventoDiscursivo(new Identificador('e2'), new ConteudoDiscursivo('Lapso '), new Posicao(1)),
            new EventoDiscursivo(new Identificador('e3'), new ConteudoDiscursivo(' LAPSO'), new Posicao(2)),
            new EventoDiscursivo(new Identificador('e4'), new ConteudoDiscursivo('Chiste'), new Posicao(3)),
        ];

        $recorrencias = (new DetectorRecorrencias())->detectar($eventos);

        $this->assertSame(['lapso' => 3, 'chiste' => 1], $recorrencias);
    }

    public function testNormalizarEPublicoEUsaAMesmaRegraDeDetectar(): void
    {
        $eventos = [
            new EventoDiscursivo(new Identificador('e1'), new ConteudoDiscursivo('Lapso'), new Posicao(0)),
        ];

        $recorrencias = (new DetectorRecorrencias())->detectar($eventos);

        $this->assertArrayHasKey(DetectorRecorrencias::normalizar(' LAPSO '), $recorrencias);
    }

    private function montarMemoria(): MemoriaLongitudinal
    {
        $memoria = new MemoriaLongitudinal(new Identificador('mem-1'));

        $sessao1 = new Sessao(new Identificador('sessao-1'), new DataSessao(new DateTimeImmutable('2026-01-10 10:00:00')));
        $discurso1 = new Discurso(new Identificador('discurso-1'), new ConteudoDiscursivo('Conteúdo A'));
        $discurso1->adicionarEvento(new EventoDiscursivo(new Identificador('e1'), new ConteudoDiscursivo('lapso'), new Posicao(0)));
        $sessao1->adicionarDiscurso($discurso1);
        $memoria->adicionarSessao($sessao1);

        $sessao2 = new Sessao(new Identificador('sessao-2'), new DataSessao(new DateTimeImmutable('2026-01-20 10:00:00')));
        $discurso2 = new Discurso(new Identificador('discurso-2'), new ConteudoDiscursivo('Conteúdo B'));
        $discurso2->adicionarEvento(new EventoDiscursivo(new Identificador('e2'), new ConteudoDiscursivo('Lapso '), new Posicao(0)));
        $discurso2->adicionarEvento(new EventoDiscursivo(new Identificador('e3'), new ConteudoDiscursivo('chiste'), new Posicao(1)));
        $sessao2->adicionarDiscurso($discurso2);
        $memoria->adicionarSessao($sessao2);

        return $memoria;
    }

    public function testDetectarCircuitoAgrupaOcorrenciasPorConteudoNormalizado(): void
    {
        $circuito = (new DetectorRecorrencias())->detectarCircuito($this->montarMemoria());

        $this->assertCount(2, $circuito['lapso']);
        $this->assertCount(1, $circuito['chiste']);
    }

    public function testDetectarCircuitoDevolveOcorrenciasEmOrdemCronologicaComOsDadosDeOrigem(): void
    {
        $circuito = (new DetectorRecorrencias())->detectarCircuito($this->montarMemoria());

        $ocorrencias = $circuito['lapso'];

        $this->assertSame('sessao-1', $ocorrencias[0]->sessaoId());
        $this->assertSame('discurso-1', $ocorrencias[0]->discursoId());
        $this->assertSame('e1', $ocorrencias[0]->eventoId());
        $this->assertSame(0, $ocorrencias[0]->posicao());
        $this->assertSame('2026-01-10 10:00:00', $ocorrencias[0]->momento()->format('Y-m-d H:i:s'));

        $this->assertSame('sessao-2', $ocorrencias[1]->sessaoId());
        $this->assertSame('discurso-2', $ocorrencias[1]->discursoId());
        $this->assertSame('e2', $ocorrencias[1]->eventoId());
        $this->assertSame('2026-01-20 10:00:00', $ocorrencias[1]->momento()->format('Y-m-d H:i:s'));
    }

    public function testDetectarCircuitoDevolveArrayVazioParaMemoriaSemSessoes(): void
    {
        $memoria = new MemoriaLongitudinal(new Identificador('mem-vazia'));

        $this->assertSame([], (new DetectorRecorrencias())->detectarCircuito($memoria));
    }
}
