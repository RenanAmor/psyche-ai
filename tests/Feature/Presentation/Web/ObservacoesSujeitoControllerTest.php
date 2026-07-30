<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\ObservacoesSujeitoController;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Tests\Support\ObservacoesHttpClientFake;

final class ObservacoesSujeitoControllerTest extends TestCase
{
    private const SUJEITOS_PADRAO = [
        'subjects' => [
            ['id' => 'sub-001', 'nome' => 'Sujeito A', 'quantidadeDeSessoes' => 2],
        ],
    ];

    public function testExibeAsRecorrenciasEObservacoesDoSujeito(): void
    {
        $fake = new ObservacoesHttpClientFake(
            self::SUJEITOS_PADRAO,
            [[
                'id' => '1',
                'descricao' => 'lapso',
                'frequencia' => 2,
                'rotuloLacaniano' => 'Estrutura candidata: deslize metonímico.',
            ]],
            [['id' => '1', 'texto' => 'Recorrência observada: lapso (2 ocorrência(s)).']]
        );

        $controller = new ObservacoesSujeitoController($fake);
        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-001/observacoes')->comRouteParams(['id' => 'sub-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Sujeito A', $resposta->corpo);
        $this->assertStringContainsString('lapso', $resposta->corpo);
        $this->assertStringContainsString('Recorrência observada: lapso (2 ocorrência(s)).', $resposta->corpo);
        $this->assertStringContainsString('Estrutura candidata: deslize metonímico.', $resposta->corpo);
    }

    public function testExibeOTrajetoDoCircuitoDaRecorrencia(): void
    {
        $fake = new ObservacoesHttpClientFake(
            recursos: self::SUJEITOS_PADRAO,
            recorrencias: [['id' => '1', 'descricao' => 'lapso', 'frequencia' => 2]],
            circuitos: [[
                'id' => '1',
                'descricao' => 'lapso',
                'frequencia' => 2,
                'rotuloLacaniano' => 'Estrutura candidata: circuito — o tema retorna ao mesmo ponto através de sessões distintas.',
                'ocorrencias' => [
                    ['sessaoId' => 'sessao-1', 'discursoId' => 'discurso-1', 'eventoId' => 'evento-1', 'momento' => '2026-01-10 10:00:00', 'posicao' => 0],
                    ['sessaoId' => 'sessao-2', 'discursoId' => 'discurso-2', 'eventoId' => 'evento-2', 'momento' => '2026-01-20 10:00:00', 'posicao' => 0],
                ],
            ]]
        );

        $controller = new ObservacoesSujeitoController($fake);
        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-001/observacoes')->comRouteParams(['id' => 'sub-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Sessão 2026-01-10 10:00:00 → Sessão 2026-01-20 10:00:00', $resposta->corpo);
        $this->assertStringContainsString(
            'Estrutura candidata: circuito — o tema retorna ao mesmo ponto através de sessões distintas.',
            $resposta->corpo
        );
    }

    public function testExibeErroDeComunicacaoQuandoOCircuitoFalha(): void
    {
        $fake = new ObservacoesHttpClientFake(
            recursos: self::SUJEITOS_PADRAO,
            falhaNoCircuito: ErrorType::COMUNICACAO
        );

        $controller = new ObservacoesSujeitoController($fake);
        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-001/observacoes')->comRouteParams(['id' => 'sub-001']));

        $this->assertSame(502, $resposta->status);
        $this->assertStringContainsString('Falha de comunicação', $resposta->corpo);
    }

    public function testExibeErroDeNaoEncontradoQuandoOSujeitoNaoExiste(): void
    {
        $fake = new ObservacoesHttpClientFake(['subjects' => []]);

        $controller = new ObservacoesSujeitoController($fake);
        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-999/observacoes')->comRouteParams(['id' => 'sub-999']));

        $this->assertSame(404, $resposta->status);
        $this->assertStringContainsString('Recurso não encontrado', $resposta->corpo);
    }

    public function testExibeErroDeComunicacaoQuandoAsObservacoesFalham(): void
    {
        $fake = new ObservacoesHttpClientFake(
            self::SUJEITOS_PADRAO,
            falhaNasObservacoes: ErrorType::COMUNICACAO
        );

        $controller = new ObservacoesSujeitoController($fake);
        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-001/observacoes')->comRouteParams(['id' => 'sub-001']));

        $this->assertSame(502, $resposta->status);
        $this->assertStringContainsString('Falha de comunicação', $resposta->corpo);
    }

    public function testExibeEstadoVazioQuandoNaoHaRecorrencias(): void
    {
        $fake = new ObservacoesHttpClientFake(self::SUJEITOS_PADRAO);

        $controller = new ObservacoesSujeitoController($fake);
        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-001/observacoes')->comRouteParams(['id' => 'sub-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Nenhuma recorrência encontrada', $resposta->corpo);
        $this->assertStringContainsString('Nenhuma observação gerada', $resposta->corpo);
        $this->assertStringContainsString('Nenhum circuito encontrado', $resposta->corpo);
    }
}
