<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Client\MockApiHttpClient;
use PsycheAI\Presentation\Web\Controllers\SessoesController;
use PsycheAI\Presentation\Web\Http\Request;

final class SessoesControllerTest extends TestCase
{
    public function testListaSessoesMockadas(): void
    {
        $controller = new SessoesController(new MockApiHttpClient());

        $resposta = $controller->index(Request::criar('GET', '/sessoes'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('2026-06-02', $resposta->corpo);
    }

    public function testEstadoVazio(): void
    {
        $controller = new SessoesController(new MockApiHttpClient(['sessoes' => []]));

        $resposta = $controller->index(Request::criar('GET', '/sessoes'));

        $this->assertStringContainsString('Nenhuma sessão registrada.', $resposta->corpo);
    }

    public function testEstadoDeCarregamento(): void
    {
        $controller = new SessoesController(new MockApiHttpClient());

        $resposta = $controller->index(Request::criar('GET', '/sessoes', ['estado' => 'carregando']));

        $this->assertStringContainsString('indicador-carregamento', $resposta->corpo);
    }
}
