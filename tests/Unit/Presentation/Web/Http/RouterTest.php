<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Http;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\Router;

final class RouterTest extends TestCase
{
    public function testDespachaParaRotaCorrespondente(): void
    {
        $router = new Router();
        $router->get('/sujeitos', static fn (Request $r): Response => Response::ok('lista'));

        $resposta = $router->despachar(Request::criar('GET', '/sujeitos'));

        $this->assertSame(200, $resposta->status);
        $this->assertSame('lista', $resposta->corpo);
    }

    public function testExtraiParametrosDeRota(): void
    {
        $router = new Router();
        $router->get('/sujeitos/{id}', static fn (Request $r): Response => Response::ok((string) $r->routeParam('id')));

        $resposta = $router->despachar(Request::criar('GET', '/sujeitos/42'));

        $this->assertSame('42', $resposta->corpo);
    }

    public function testNaoCasaMetodoDiferente(): void
    {
        $router = new Router();
        $router->get('/sujeitos', static fn (Request $r): Response => Response::ok('lista'));

        $resposta = $router->despachar(Request::criar('POST', '/sujeitos'));

        $this->assertSame(404, $resposta->status);
    }

    public function testUsaHandlerNaoEncontradoQuandoConfigurado(): void
    {
        $router = new Router();
        $router->naoEncontradoHandler(static fn (Request $r): Response => Response::naoEncontrado('customizado'));

        $resposta = $router->despachar(Request::criar('GET', '/rota-qualquer'));

        $this->assertSame(404, $resposta->status);
        $this->assertSame('customizado', $resposta->corpo);
    }

    public function testSemHandlerNaoEncontradoDevolveRespostaPadrao(): void
    {
        $router = new Router();

        $resposta = $router->despachar(Request::criar('GET', '/rota-qualquer'));

        $this->assertSame(404, $resposta->status);
        $this->assertStringContainsString('/rota-qualquer', $resposta->corpo);
    }
}
