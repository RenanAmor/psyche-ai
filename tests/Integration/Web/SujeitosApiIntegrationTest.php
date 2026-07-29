<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Web;

use PsycheAI\Presentation\Web\Client\ApiHttpClient;
use PsycheAI\Presentation\Web\Controllers\SujeitosController;
use PsycheAI\Presentation\Web\Http\Request;

/**
 * Exercita a interface web (Controller) sobre ApiHttpClient falando HTTP
 * de verdade com a API REST (Sprint 10) subida por RealApiTestCase — sem
 * nenhum mock. Cobre o CRUD completo e os cenários reais de erro (404,
 * 409/422 traduzidos para o catálogo de erro da interface, e falha de
 * comunicação por indisponibilidade real do serviço).
 */
final class SujeitosApiIntegrationTest extends RealApiTestCase
{
    public function testFluxoCompletoDeCrudDeSujeitoAtravesDaApiReal(): void
    {
        $controller = new SujeitosController(new ApiHttpClient(self::$baseUrl));

        $store = $controller->store(Request::criar('POST', '/sujeitos', [], ['id' => 'sub-real-1', 'nome' => 'Sujeito Real']));
        self::assertSame(200, $store->status);
        self::assertStringContainsString('Sujeito Real', $store->corpo);

        $mostrar = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-real-1')->comRouteParams(['id' => 'sub-real-1']));
        self::assertSame(200, $mostrar->status);
        self::assertStringContainsString('Sujeito Real', $mostrar->corpo);

        $editar = $controller->editar(Request::criar('GET', '/sujeitos/sub-real-1/editar')->comRouteParams(['id' => 'sub-real-1']));
        self::assertSame(200, $editar->status);
        self::assertStringContainsString('value="Sujeito Real"', $editar->corpo);

        $atualizar = $controller->atualizar(
            Request::criar('POST', '/sujeitos/sub-real-1', [], ['nome' => 'Sujeito Renomeado'])->comRouteParams(['id' => 'sub-real-1'])
        );
        self::assertSame(200, $atualizar->status);

        $mostrarAtualizado = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-real-1')->comRouteParams(['id' => 'sub-real-1']));
        self::assertStringContainsString('Sujeito Renomeado', $mostrarAtualizado->corpo);

        $excluir = $controller->excluir(Request::criar('POST', '/sujeitos/sub-real-1/excluir')->comRouteParams(['id' => 'sub-real-1']));
        self::assertSame(200, $excluir->status);

        $mostrarApagado = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-real-1')->comRouteParams(['id' => 'sub-real-1']));
        self::assertSame(404, $mostrarApagado->status);
    }

    public function testErroDeNaoEncontradoRealDaApiExibePaginaDeErro404(): void
    {
        $controller = new SujeitosController(new ApiHttpClient(self::$baseUrl));

        $resposta = $controller->mostrar(
            Request::criar('GET', '/sujeitos/nunca-existiu')->comRouteParams(['id' => 'nunca-existiu'])
        );

        self::assertSame(404, $resposta->status);
        self::assertStringContainsString('Recurso não encontrado', $resposta->corpo);
    }

    public function testErroDeConflitoRealDaApiAoCriarIdDuplicadoReexibeFormulario(): void
    {
        $controller = new SujeitosController(new ApiHttpClient(self::$baseUrl));

        $controller->store(Request::criar('POST', '/sujeitos', [], ['id' => 'sub-duplicado', 'nome' => 'Original']));

        $resposta = $controller->store(Request::criar('POST', '/sujeitos', [], ['id' => 'sub-duplicado', 'nome' => 'Outro']));

        self::assertSame(422, $resposta->status);
        self::assertStringContainsString('já existe', $resposta->corpo);
    }

    public function testFalhaDeComunicacaoRealQuandoApiEstaIndisponivel(): void
    {
        $portaFechada = self::portaFechada();
        $controller = new SujeitosController(new ApiHttpClient(sprintf('http://127.0.0.1:%d', $portaFechada), 1));

        $resposta = $controller->index(Request::criar('GET', '/sujeitos'));

        self::assertSame(502, $resposta->status);
        self::assertStringContainsString('Falha de comunicação', $resposta->corpo);
    }
}
