<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\ViewModels;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\ViewModels\CircuitoRecorrenciaViewModel;
use PsycheAI\Presentation\Web\ViewModels\GrafoCircuitoViewModel;

final class GrafoCircuitoViewModelTest extends TestCase
{
    public function testListaVaziaProduzGrafoVazio(): void
    {
        $grafo = GrafoCircuitoViewModel::apartirDosCircuitos([])->toArray();

        $this->assertSame(['nos' => [], 'arestas' => []], $grafo);
    }

    public function testProduzUmaArestaAMenosQueOcorrenciasPorCircuito(): void
    {
        $circuito = CircuitoRecorrenciaViewModel::fromArray([
            'id' => '1',
            'descricao' => 'lapso',
            'frequencia' => 3,
            'rotuloLacaniano' => 'Estrutura candidata: circuito — o tema retorna ao mesmo ponto através de sessões distintas.',
            'ocorrencias' => [
                ['sessaoId' => 'sessao-1', 'discursoId' => 'd1', 'eventoId' => 'e1', 'momento' => '2026-01-10 10:00:00', 'posicao' => 0],
                ['sessaoId' => 'sessao-2', 'discursoId' => 'd2', 'eventoId' => 'e2', 'momento' => '2026-01-20 10:00:00', 'posicao' => 0],
                ['sessaoId' => 'sessao-3', 'discursoId' => 'd3', 'eventoId' => 'e3', 'momento' => '2026-01-30 10:00:00', 'posicao' => 0],
            ],
        ]);

        $grafo = GrafoCircuitoViewModel::apartirDosCircuitos([$circuito])->toArray();

        $this->assertCount(3, $grafo['nos']);
        $this->assertCount(2, $grafo['arestas']);
        $this->assertSame('sessao-1', $grafo['arestas'][0]['origem']);
        $this->assertSame('sessao-2', $grafo['arestas'][0]['destino']);
        $this->assertSame(
            'Estrutura candidata: circuito — o tema retorna ao mesmo ponto através de sessões distintas.',
            $grafo['arestas'][0]['rotuloLacaniano']
        );
    }

    public function testDeduplicaNoQuandoAMesmaSessaoApareceEmCircuitosDiferentes(): void
    {
        $circuitoA = CircuitoRecorrenciaViewModel::fromArray([
            'id' => '1',
            'descricao' => 'lapso',
            'frequencia' => 2,
            'ocorrencias' => [
                ['sessaoId' => 'sessao-1', 'discursoId' => 'd1', 'eventoId' => 'e1', 'momento' => '2026-01-10 10:00:00', 'posicao' => 0],
                ['sessaoId' => 'sessao-2', 'discursoId' => 'd2', 'eventoId' => 'e2', 'momento' => '2026-01-20 10:00:00', 'posicao' => 0],
            ],
        ]);

        $circuitoB = CircuitoRecorrenciaViewModel::fromArray([
            'id' => '2',
            'descricao' => 'chiste',
            'frequencia' => 2,
            'ocorrencias' => [
                ['sessaoId' => 'sessao-1', 'discursoId' => 'd3', 'eventoId' => 'e3', 'momento' => '2026-01-10 10:00:00', 'posicao' => 1],
                ['sessaoId' => 'sessao-3', 'discursoId' => 'd4', 'eventoId' => 'e4', 'momento' => '2026-01-25 10:00:00', 'posicao' => 0],
            ],
        ]);

        $grafo = GrafoCircuitoViewModel::apartirDosCircuitos([$circuitoA, $circuitoB])->toArray();

        $this->assertCount(3, $grafo['nos']);
        $this->assertCount(2, $grafo['arestas']);
    }

    public function testRotuloLacanianoNuloEPreservadoSemQuebrar(): void
    {
        $circuito = CircuitoRecorrenciaViewModel::fromArray([
            'id' => '1',
            'descricao' => 'lapso',
            'frequencia' => 2,
            'ocorrencias' => [
                ['sessaoId' => 'sessao-1', 'discursoId' => 'd1', 'eventoId' => 'e1', 'momento' => '2026-01-10 10:00:00', 'posicao' => 0],
                ['sessaoId' => 'sessao-2', 'discursoId' => 'd2', 'eventoId' => 'e2', 'momento' => '2026-01-20 10:00:00', 'posicao' => 0],
            ],
        ]);

        $grafo = GrafoCircuitoViewModel::apartirDosCircuitos([$circuito])->toArray();

        $this->assertNull($grafo['arestas'][0]['rotuloLacaniano']);
    }
}
