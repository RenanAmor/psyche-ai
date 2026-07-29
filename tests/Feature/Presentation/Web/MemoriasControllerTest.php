<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\MemoriasController;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Tests\Support\HttpClientStub;

final class MemoriasControllerTest extends TestCase
{
    public function testListaMemoriasMockadas(): void
    {
        $controller = new MemoriasController(new HttpClientStub());

        $resposta = $controller->index(Request::criar('GET', '/memorias'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('mem-001', $resposta->corpo);
    }

    public function testEstadoVazio(): void
    {
        $controller = new MemoriasController(new HttpClientStub(['memories' => []]));

        $resposta = $controller->index(Request::criar('GET', '/memorias'));

        $this->assertStringContainsString('Nenhuma memória longitudinal construída.', $resposta->corpo);
    }

    public function testNovoExibeFormularioVazio(): void
    {
        $controller = new MemoriasController(new HttpClientStub());

        $resposta = $controller->novo(Request::criar('GET', '/memorias/novo'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
    }

    public function testStoreComCamposValidosRedirecionaParaListaAtualizada(): void
    {
        $controller = new MemoriasController(new HttpClientStub());

        $resposta = $controller->store(Request::criar('POST', '/memorias', [], ['id' => 'mem-999', 'sujeitoId' => 'sub-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<table', $resposta->corpo);
    }

    public function testStoreComCamposAusentesReexibeFormularioComErros(): void
    {
        $controller = new MemoriasController(new HttpClientStub());

        $resposta = $controller->store(Request::criar('POST', '/memorias', [], []));

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('O campo id é obrigatório.', $resposta->corpo);
        $this->assertStringContainsString('O campo sujeitoId é obrigatório.', $resposta->corpo);
    }

    public function testMostrarExibeODetalheDaMemoria(): void
    {
        $controller = new MemoriasController(new HttpClientStub());

        $resposta = $controller->mostrar(Request::criar('GET', '/memorias/mem-001')->comRouteParams(['id' => 'mem-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('mem-001', $resposta->corpo);
    }

    public function testMostrarComIdInexistenteExibePaginaDeErro(): void
    {
        $controller = new MemoriasController(new HttpClientStub());

        $resposta = $controller->mostrar(Request::criar('GET', '/memorias/inexistente')->comRouteParams(['id' => 'inexistente']));

        $this->assertSame(404, $resposta->status);
    }

    public function testEditarExibeFormulario(): void
    {
        $controller = new MemoriasController(new HttpClientStub());

        $resposta = $controller->editar(Request::criar('GET', '/memorias/mem-001/editar')->comRouteParams(['id' => 'mem-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
    }

    public function testAtualizarComSujeitoIdValidoRedirecionaParaListaAtualizada(): void
    {
        $controller = new MemoriasController(new HttpClientStub());

        $resposta = $controller->atualizar(
            Request::criar('POST', '/memorias/mem-001', [], ['sujeitoId' => 'sub-001'])->comRouteParams(['id' => 'mem-001'])
        );

        $this->assertSame(200, $resposta->status);
    }

    public function testAtualizarComSujeitoIdVazioReexibeFormularioComErro(): void
    {
        $controller = new MemoriasController(new HttpClientStub());

        $resposta = $controller->atualizar(
            Request::criar('POST', '/memorias/mem-001', [], ['sujeitoId' => ''])->comRouteParams(['id' => 'mem-001'])
        );

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('O campo sujeitoId é obrigatório.', $resposta->corpo);
    }

    public function testExcluirRemoveAMemoriaERedirecionaParaLista(): void
    {
        $controller = new MemoriasController(new HttpClientStub());

        $resposta = $controller->excluir(Request::criar('POST', '/memorias/mem-001/excluir')->comRouteParams(['id' => 'mem-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Nenhuma memória longitudinal construída.', $resposta->corpo);
    }
}
