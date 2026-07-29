<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Client\MockApiHttpClient;
use PsycheAI\Presentation\Web\Controllers\DiscursosController;
use PsycheAI\Presentation\Web\Http\Request;

final class DiscursosControllerTest extends TestCase
{
    public function testListaDiscursosMockados(): void
    {
        $controller = new DiscursosController(new MockApiHttpClient());

        $resposta = $controller->index(Request::criar('GET', '/discursos'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Relato inicial da sessão.', $resposta->corpo);
    }

    public function testEstadoVazio(): void
    {
        $controller = new DiscursosController(new MockApiHttpClient(['discursos' => []]));

        $resposta = $controller->index(Request::criar('GET', '/discursos'));

        $this->assertStringContainsString('Nenhum discurso registrado.', $resposta->corpo);
    }
}
