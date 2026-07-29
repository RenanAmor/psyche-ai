<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Client\MockApiHttpClient;
use PsycheAI\Presentation\Web\Controllers\MemoriasController;
use PsycheAI\Presentation\Web\Http\Request;

final class MemoriasControllerTest extends TestCase
{
    public function testListaMemoriasMockadas(): void
    {
        $controller = new MemoriasController(new MockApiHttpClient());

        $resposta = $controller->index(Request::criar('GET', '/memorias'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('mem-001', $resposta->corpo);
    }

    public function testEstadoVazio(): void
    {
        $controller = new MemoriasController(new MockApiHttpClient(['memorias' => []]));

        $resposta = $controller->index(Request::criar('GET', '/memorias'));

        $this->assertStringContainsString('Nenhuma memória longitudinal construída.', $resposta->corpo);
    }
}
