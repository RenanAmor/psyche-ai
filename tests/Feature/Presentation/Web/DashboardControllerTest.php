<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Client\MockApiHttpClient;
use PsycheAI\Presentation\Web\Controllers\DashboardController;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Http\Request;

final class DashboardControllerTest extends TestCase
{
    public function testExibeUmCartaoPorRecursoComOsTotaisMockados(): void
    {
        $controller = new DashboardController(new MockApiHttpClient());

        $resposta = $controller->index(Request::criar('GET', '/'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Sujeitos', $resposta->corpo);
        $this->assertStringContainsString('Sessões', $resposta->corpo);
        $this->assertStringContainsString('Discursos', $resposta->corpo);
        $this->assertStringContainsString('Memórias', $resposta->corpo);
        $this->assertStringContainsString('Eventos Discursivos', $resposta->corpo);
        $this->assertStringContainsString('cartao-valor">3<', $resposta->corpo);
    }

    public function testEstadoDeCarregamento(): void
    {
        $controller = new DashboardController(new MockApiHttpClient());

        $resposta = $controller->index(Request::criar('GET', '/', ['estado' => 'carregando']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('indicador-carregamento', $resposta->corpo);
    }

    public function testFalhaDeComunicacaoExibePaginaDeErro(): void
    {
        $controller = new DashboardController(MockApiHttpClient::comFalha(ErrorType::COMUNICACAO));

        $resposta = $controller->index(Request::criar('GET', '/'));

        $this->assertSame(502, $resposta->status);
        $this->assertStringContainsString('Falha de comunicação', $resposta->corpo);
    }
}
