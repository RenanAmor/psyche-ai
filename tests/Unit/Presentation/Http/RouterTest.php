<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Http;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Http\HttpException;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Http\Router;

final class RouterTest extends TestCase
{
    public function testDespachaParaOHandlerDaRotaComPlaceholder(): void
    {
        $router = new Router();
        $router->get('/sessions/{id}', function (Request $request, array $params): JsonResponse {
            return JsonResponse::sucesso(['idRecebido' => $params['id']]);
        });

        $response = $router->despachar(Request::criar('GET', '/sessions/sessao-1'));

        self::assertSame('sessao-1', json_decode($response->corpo(), true)['data']['idRecebido']);
    }

    public function testMetodoDiferenteNaoCasaComARota(): void
    {
        $router = new Router();
        $router->get('/sessions', fn (Request $r, array $p) => JsonResponse::sucesso([]));

        $this->expectException(HttpException::class);

        $router->despachar(Request::criar('POST', '/sessions'));
    }

    public function testRotaDesconhecidaLancaHttpException404(): void
    {
        $router = new Router();

        try {
            $router->despachar(Request::criar('GET', '/inexistente'));
            self::fail('Deveria ter lançado HttpException.');
        } catch (HttpException $erro) {
            self::assertSame(404, $erro->statusCode());
        }
    }
}
