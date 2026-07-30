<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\HistoricoSujeitoController;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Tests\Support\HistoricoHttpClientFake;

final class HistoricoSujeitoControllerTest extends TestCase
{
    private const SUJEITOS_PADRAO = [
        'subjects' => [
            ['id' => 'sub-001', 'nome' => 'Sujeito A', 'quantidadeDeSessoes' => 2],
        ],
    ];

    public function testExibeOHistoricoComTimelineEConsolidacao(): void
    {
        $fake = new HistoricoHttpClientFake(
            self::SUJEITOS_PADRAO,
            [
                ['tipo' => 'sessao', 'id' => 'ses-1', 'timestamp' => '2026-01-10 10:00:00', 'dados' => ['quantidadeDeDiscursos' => 1]],
                ['tipo' => 'evento', 'id' => 'evt-1', 'timestamp' => '2026-01-10 10:05:00', 'dados' => ['conteudo' => 'Lapso registrado']],
            ],
            [
                'sujeitoId' => 'sub-001',
                'quantidadeDeSessoes' => 1,
                'quantidadeDeDiscursos' => 1,
                'quantidadeDeEventosDiscursivos' => 1,
                'quantidadeDeMemorias' => 0,
            ]
        );

        $controller = new HistoricoSujeitoController($fake);
        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-001/historico')->comRouteParams(['id' => 'sub-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Sujeito A', $resposta->corpo);
        $this->assertStringContainsString('Lapso registrado', $resposta->corpo);
    }

    public function testExibeErroDeNaoEncontradoQuandoOSujeitoNaoExiste(): void
    {
        $fake = new HistoricoHttpClientFake(['subjects' => []]);

        $controller = new HistoricoSujeitoController($fake);
        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-999/historico')->comRouteParams(['id' => 'sub-999']));

        $this->assertSame(404, $resposta->status);
        $this->assertStringContainsString('Recurso não encontrado', $resposta->corpo);
    }

    public function testExibeErroDeComunicacaoQuandoATimelineFalha(): void
    {
        $fake = new HistoricoHttpClientFake(
            self::SUJEITOS_PADRAO,
            falhaNaLinhaDoTempo: ErrorType::COMUNICACAO
        );

        $controller = new HistoricoSujeitoController($fake);
        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-001/historico')->comRouteParams(['id' => 'sub-001']));

        $this->assertSame(502, $resposta->status);
        $this->assertStringContainsString('Falha de comunicação', $resposta->corpo);
    }

    public function testExibeErroDeTimeoutQuandoAConsolidacaoFalha(): void
    {
        $fake = new HistoricoHttpClientFake(
            self::SUJEITOS_PADRAO,
            falhaNaConsolidacao: ErrorType::TIMEOUT
        );

        $controller = new HistoricoSujeitoController($fake);
        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-001/historico')->comRouteParams(['id' => 'sub-001']));

        $this->assertSame(504, $resposta->status);
        $this->assertStringContainsString('Tempo de espera esgotado', $resposta->corpo);
    }

    public function testExibeErroInternoQuandoATimelineFalhaComErroInterno(): void
    {
        $fake = new HistoricoHttpClientFake(
            self::SUJEITOS_PADRAO,
            falhaNaLinhaDoTempo: ErrorType::INTERNO
        );

        $controller = new HistoricoSujeitoController($fake);
        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-001/historico')->comRouteParams(['id' => 'sub-001']));

        $this->assertSame(500, $resposta->status);
        $this->assertStringContainsString('Erro interno', $resposta->corpo);
    }

    public function testRepassaOsFiltrosDaQueryStringParaAConsultaDaTimeline(): void
    {
        $fake = new HistoricoHttpClientFake(self::SUJEITOS_PADRAO);

        $controller = new HistoricoSujeitoController($fake);
        $controller->mostrar(
            Request::criar('GET', '/sujeitos/sub-001/historico', ['tipo' => 'evento', 'q' => 'Lapso', 'pagina' => '2'])
                ->comRouteParams(['id' => 'sub-001'])
        );

        $this->assertSame(['tipo' => 'evento', 'q' => 'Lapso', 'pagina' => '2'], $fake->ultimosParametrosDeTimeline);
    }
}
