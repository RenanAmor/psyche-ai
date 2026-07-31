<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Components;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Components\CircuitoTrajetoComponent;
use PsycheAI\Presentation\Web\ViewModels\CircuitoRecorrenciaViewModel;

final class CircuitoTrajetoComponentTest extends TestCase
{
    public function testRenderizaOTrajetoDeCadaCircuito(): void
    {
        $circuitos = CircuitoRecorrenciaViewModel::fromArrayList([
            [
                'id' => '1',
                'descricao' => 'lapso',
                'frequencia' => 2,
                'ocorrencias' => [
                    ['sessaoId' => 'sessao-1', 'discursoId' => 'd1', 'eventoId' => 'e1', 'momento' => '2026-01-10 10:00:00', 'posicao' => 0],
                    ['sessaoId' => 'sessao-2', 'discursoId' => 'd2', 'eventoId' => 'e2', 'momento' => '2026-01-20 10:00:00', 'posicao' => 0],
                ],
            ],
        ]);

        $html = CircuitoTrajetoComponent::render($circuitos);

        $this->assertStringContainsString('<ul', $html);
        $this->assertStringContainsString('lapso', $html);
        $this->assertStringContainsString('Sessão 2026-01-10 10:00:00 → Sessão 2026-01-20 10:00:00', $html);
    }

    public function testRenderizaORotuloLacanianoQuandoPresente(): void
    {
        $circuitos = CircuitoRecorrenciaViewModel::fromArrayList([
            [
                'id' => '1',
                'descricao' => 'lapso',
                'frequencia' => 2,
                'rotuloLacaniano' => 'Estrutura candidata: circuito.',
                'ocorrencias' => [],
            ],
        ]);

        $html = CircuitoTrajetoComponent::render($circuitos);

        $this->assertStringContainsString('Estrutura candidata: circuito.', $html);
    }

    public function testRenderizaAFundamentacaoTeoricaQuandoPresente(): void
    {
        $circuitos = CircuitoRecorrenciaViewModel::fromArrayList([
            [
                'id' => '1',
                'descricao' => 'lapso',
                'frequencia' => 2,
                'rotuloLacaniano' => 'Estrutura candidata: circuito.',
                'fundamentacaoTeorica' => 'Circuito: o mesmo conteúdo reaparece em ≥2 sessões.',
                'ocorrencias' => [],
            ],
        ]);

        $html = CircuitoTrajetoComponent::render($circuitos);

        $this->assertStringContainsString('fundamentacao-lacaniana', $html);
        $this->assertStringContainsString('Circuito: o mesmo conteúdo reaparece em ≥2 sessões.', $html);
    }

    public function testNaoRenderizaFundamentacaoQuandoAusente(): void
    {
        $circuitos = CircuitoRecorrenciaViewModel::fromArrayList([
            ['id' => '1', 'descricao' => 'lapso', 'frequencia' => 2, 'ocorrencias' => []],
        ]);

        $html = CircuitoTrajetoComponent::render($circuitos);

        $this->assertStringNotContainsString('fundamentacao-lacaniana', $html);
    }

    public function testEscapaConteudo(): void
    {
        $circuitos = CircuitoRecorrenciaViewModel::fromArrayList([
            ['id' => '1', 'descricao' => '<script>alert(1)</script>', 'frequencia' => 2, 'ocorrencias' => []],
        ]);

        $html = CircuitoTrajetoComponent::render($circuitos);

        $this->assertStringNotContainsString('<script>alert', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function testDevolveEstadoVazioQuandoNaoHaCircuitos(): void
    {
        $html = CircuitoTrajetoComponent::render([], 'Nenhum circuito.');

        $this->assertStringNotContainsString('<ul', $html);
        $this->assertStringContainsString('estado-vazio', $html);
        $this->assertStringContainsString('Nenhum circuito.', $html);
    }
}
