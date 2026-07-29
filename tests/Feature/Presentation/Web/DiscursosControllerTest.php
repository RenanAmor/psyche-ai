<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\DiscursosController;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Tests\Support\HttpClientStub;

final class DiscursosControllerTest extends TestCase
{
    public function testListaDiscursosMockados(): void
    {
        $controller = new DiscursosController(new HttpClientStub());

        $resposta = $controller->index(Request::criar('GET', '/discursos'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Relato inicial da sessão.', $resposta->corpo);
    }

    public function testEstadoVazio(): void
    {
        $controller = new DiscursosController(new HttpClientStub(['discourses' => []]));

        $resposta = $controller->index(Request::criar('GET', '/discursos'));

        $this->assertStringContainsString('Nenhum discurso registrado.', $resposta->corpo);
    }

    public function testNovoExibeFormularioComTextareaParaTextosExtensos(): void
    {
        $controller = new DiscursosController(new HttpClientStub());

        $resposta = $controller->novo(Request::criar('GET', '/discursos/novo'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<textarea', $resposta->corpo);
    }

    public function testStoreComCamposValidosRedirecionaParaListaAtualizada(): void
    {
        $controller = new DiscursosController(new HttpClientStub());

        $textoExtenso = str_repeat('Um relato bastante longo sobre a sessão. ', 50);

        $resposta = $controller->store(Request::criar('POST', '/discursos', [], [
            'id' => 'dsc-999',
            'sessaoId' => 'ses-001',
            'conteudo' => $textoExtenso,
        ]));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<table', $resposta->corpo);
    }

    public function testStoreComCamposAusentesReexibeFormularioComErros(): void
    {
        $controller = new DiscursosController(new HttpClientStub());

        $resposta = $controller->store(Request::criar('POST', '/discursos', [], ['id' => 'dsc-999']));

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('O campo sessaoId é obrigatório.', $resposta->corpo);
        $this->assertStringContainsString('O campo conteudo é obrigatório.', $resposta->corpo);
    }

    public function testMostrarExibeODetalheDoDiscurso(): void
    {
        $controller = new DiscursosController(new HttpClientStub());

        $resposta = $controller->mostrar(Request::criar('GET', '/discursos/dsc-001')->comRouteParams(['id' => 'dsc-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Relato inicial da sessão.', $resposta->corpo);
    }

    public function testMostrarComIdInexistenteExibePaginaDeErro(): void
    {
        $controller = new DiscursosController(new HttpClientStub());

        $resposta = $controller->mostrar(Request::criar('GET', '/discursos/inexistente')->comRouteParams(['id' => 'inexistente']));

        $this->assertSame(404, $resposta->status);
    }

    public function testEditarExibeFormularioPreenchido(): void
    {
        $controller = new DiscursosController(new HttpClientStub());

        $resposta = $controller->editar(Request::criar('GET', '/discursos/dsc-001/editar')->comRouteParams(['id' => 'dsc-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Relato inicial da sessão.', $resposta->corpo);
    }

    public function testAtualizarComConteudoValidoRedirecionaParaListaAtualizada(): void
    {
        $controller = new DiscursosController(new HttpClientStub());

        $resposta = $controller->atualizar(
            Request::criar('POST', '/discursos/dsc-001', [], ['conteudo' => 'Conteúdo revisado'])->comRouteParams(['id' => 'dsc-001'])
        );

        $this->assertSame(200, $resposta->status);
    }

    public function testAtualizarComConteudoVazioReexibeFormularioComErro(): void
    {
        $controller = new DiscursosController(new HttpClientStub());

        $resposta = $controller->atualizar(
            Request::criar('POST', '/discursos/dsc-001', [], ['conteudo' => ''])->comRouteParams(['id' => 'dsc-001'])
        );

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('O campo conteudo é obrigatório.', $resposta->corpo);
    }

    public function testExcluirRemoveODiscursoERedirecionaParaLista(): void
    {
        $controller = new DiscursosController(new HttpClientStub());

        $resposta = $controller->excluir(Request::criar('POST', '/discursos/dsc-001/excluir')->comRouteParams(['id' => 'dsc-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringNotContainsString('Relato inicial da sessão.', $resposta->corpo);
    }
}
