<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Application\UseCases\DetectarCircuitoRecorrencia\DetectarCircuitoRecorrenciaCommand;
use PsycheAI\Application\UseCases\DetectarCircuitoRecorrencia\DetectarCircuitoRecorrenciaHandler;
use PsycheAI\Application\UseCases\DetectarRecorrencias\DetectarRecorrenciasCommand;
use PsycheAI\Application\UseCases\DetectarRecorrencias\DetectarRecorrenciasHandler;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;

final class DetectarCircuitoRecorrenciaHandlerTest extends TestCase
{
    private function construirMemoriaComDuasSessoes(): MemoriaLongitudinal
    {
        $memoria = new MemoriaLongitudinal(new Identificador('mem1'));

        $discurso1 = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('conteudo 1'));
        $discurso1->adicionarEvento(new EventoDiscursivo(new Identificador('e1'), new ConteudoDiscursivo('lapso'), new Posicao(0)));
        $sessao1 = new Sessao(new Identificador('s1'), new DataSessao(new DateTimeImmutable('2026-01-10')));
        $sessao1->adicionarDiscurso($discurso1);
        $memoria->adicionarSessao($sessao1);

        $discurso2 = new Discurso(new Identificador('d2'), new ConteudoDiscursivo('conteudo 2'));
        $discurso2->adicionarEvento(new EventoDiscursivo(new Identificador('e2'), new ConteudoDiscursivo('lapso'), new Posicao(0)));
        $discurso2->adicionarEvento(new EventoDiscursivo(new Identificador('e3'), new ConteudoDiscursivo('chiste'), new Posicao(1)));
        $sessao2 = new Sessao(new Identificador('s2'), new DataSessao(new DateTimeImmutable('2026-01-20')));
        $sessao2->adicionarDiscurso($discurso2);
        $memoria->adicionarSessao($sessao2);

        return $memoria;
    }

    public function testAssociaOCircuitoAoIdDaRecorrenciaCorrespondente(): void
    {
        $memoria = $this->construirMemoriaComDuasSessoes();
        $recorrencias = (new DetectarRecorrenciasHandler())->handle(new DetectarRecorrenciasCommand($memoria))->recorrencias();

        $resultado = (new DetectarCircuitoRecorrenciaHandler())->handle(
            new DetectarCircuitoRecorrenciaCommand($memoria, $recorrencias)
        );

        $circuitos = $resultado->circuitosPorRecorrencia();
        $idLapso = $recorrencias[0]->id()->valor();

        $this->assertCount(1, $recorrencias);
        $this->assertArrayHasKey($idLapso, $circuitos);
        $this->assertCount(2, $circuitos[$idLapso]);
        $this->assertSame('s1', $circuitos[$idLapso][0]->sessaoId());
        $this->assertSame('s2', $circuitos[$idLapso][1]->sessaoId());
    }

    public function testNaoIntroduzRecorrenciaQueNaoTenhaSidoPassada(): void
    {
        $memoria = $this->construirMemoriaComDuasSessoes();

        $resultado = (new DetectarCircuitoRecorrenciaHandler())->handle(
            new DetectarCircuitoRecorrenciaCommand($memoria, [])
        );

        $this->assertSame([], $resultado->circuitosPorRecorrencia());
    }
}
