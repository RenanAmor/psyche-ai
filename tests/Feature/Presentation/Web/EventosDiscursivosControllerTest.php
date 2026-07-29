<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Client\MockApiHttpClient;
use PsycheAI\Presentation\Web\Controllers\EventosDiscursivosController;
use PsycheAI\Presentation\Web\Http\Request;

final class EventosDiscursivosControllerTest extends TestCase
{
    public function testEstadoVazioPorPadrao(): void
    {
        $controller = new EventosDiscursivosController(new MockApiHttpClient());

        $resposta = $controller->index(Request::criar('GET', '/eventos-discursivos'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Nenhum evento discursivo registrado.', $resposta->corpo);
    }

    public function testListaEventosQuandoHaDados(): void
    {
        $controller = new EventosDiscursivosController(new MockApiHttpClient([
            'eventos-discursivos' => [['id' => 'evt-1', 'conteudo' => 'Ato falho', 'posicao' => 1]],
        ]));

        $resposta = $controller->index(Request::criar('GET', '/eventos-discursivos'));

        $this->assertStringContainsString('Ato falho', $resposta->corpo);
    }
}
