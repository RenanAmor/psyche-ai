<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\SujeitosController;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Tests\Support\HttpClientStub;

final class SujeitosControllerTest extends TestCase
{
    public function testListaSujeitosMockados(): void
    {
        $controller = new SujeitosController(new HttpClientStub());

        $resposta = $controller->index(Request::criar('GET', '/sujeitos'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Sujeito A', $resposta->corpo);
        $this->assertStringContainsString('<table', $resposta->corpo);
    }

    public function testEstadoVazioQuandoNaoHaSujeitos(): void
    {
        $controller = new SujeitosController(new HttpClientStub(['subjects' => []]));

        $resposta = $controller->index(Request::criar('GET', '/sujeitos'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('estado-vazio', $resposta->corpo);
        $this->assertStringContainsString('Nenhum sujeito cadastrado.', $resposta->corpo);
    }

    public function testEstadoDeCarregamento(): void
    {
        $controller = new SujeitosController(new HttpClientStub());

        $resposta = $controller->index(Request::criar('GET', '/sujeitos', ['estado' => 'carregando']));

        $this->assertStringContainsString('indicador-carregamento', $resposta->corpo);
    }

    public function testFalhaNaoEncontradoParaRecursoDesconhecido(): void
    {
        $controller = new SujeitosController(new HttpClientStub(['outro' => []]));

        $resposta = $controller->index(Request::criar('GET', '/sujeitos'));

        $this->assertSame(404, $resposta->status);
        $this->assertStringContainsString('Recurso não encontrado', $resposta->corpo);
    }

    public function testFalhaInternaExibePaginaDeErro(): void
    {
        $controller = new SujeitosController(HttpClientStub::comFalha(ErrorType::INTERNO));

        $resposta = $controller->index(Request::criar('GET', '/sujeitos'));

        $this->assertSame(500, $resposta->status);
        $this->assertStringContainsString('Erro interno', $resposta->corpo);
    }

    public function testNovoExibeFormularioVazio(): void
    {
        $controller = new SujeitosController(new HttpClientStub());

        $resposta = $controller->novo(Request::criar('GET', '/sujeitos/novo'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
        $this->assertStringNotContainsString('mensagem-erro', $resposta->corpo);
    }

    public function testStoreComNomeVazioReexibeFormularioComErroDeValidacao(): void
    {
        $controller = new SujeitosController(new HttpClientStub());

        $resposta = $controller->store(Request::criar('POST', '/sujeitos', [], ['id' => 'sub-999', 'nome' => '  ']));

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('O campo nome é obrigatório.', $resposta->corpo);
    }

    public function testStoreComIdAusenteReexibeFormularioComErroDeValidacao(): void
    {
        $controller = new SujeitosController(new HttpClientStub());

        $resposta = $controller->store(Request::criar('POST', '/sujeitos', [], ['nome' => 'Nova Pessoa']));

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('O campo id é obrigatório.', $resposta->corpo);
    }

    public function testStoreComDadosValidosRedirecionaParaListaAtualizada(): void
    {
        $controller = new SujeitosController(new HttpClientStub());

        $resposta = $controller->store(Request::criar('POST', '/sujeitos', [], ['id' => 'sub-999', 'nome' => 'Nova Pessoa']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<table', $resposta->corpo);
    }

    public function testMostrarExibeODetalheDoSujeito(): void
    {
        $controller = new SujeitosController(new HttpClientStub());

        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/sub-001')->comRouteParams(['id' => 'sub-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Sujeito A', $resposta->corpo);
    }

    public function testMostrarComIdInexistenteExibePaginaDeErro(): void
    {
        $controller = new SujeitosController(new HttpClientStub());

        $resposta = $controller->mostrar(Request::criar('GET', '/sujeitos/inexistente')->comRouteParams(['id' => 'inexistente']));

        $this->assertSame(404, $resposta->status);
    }

    public function testEditarExibeFormularioPreenchido(): void
    {
        $controller = new SujeitosController(new HttpClientStub());

        $resposta = $controller->editar(Request::criar('GET', '/sujeitos/sub-001/editar')->comRouteParams(['id' => 'sub-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('value="Sujeito A"', $resposta->corpo);
    }

    public function testAtualizarComNomeValidoRedirecionaParaListaAtualizada(): void
    {
        $controller = new SujeitosController(new HttpClientStub());

        $resposta = $controller->atualizar(
            Request::criar('POST', '/sujeitos/sub-001', [], ['nome' => 'Sujeito Renomeado'])->comRouteParams(['id' => 'sub-001'])
        );

        $this->assertSame(200, $resposta->status);
    }

    public function testAtualizarComNomeVazioReexibeFormularioComErro(): void
    {
        $controller = new SujeitosController(new HttpClientStub());

        $resposta = $controller->atualizar(
            Request::criar('POST', '/sujeitos/sub-001', [], ['nome' => ''])->comRouteParams(['id' => 'sub-001'])
        );

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('O campo nome é obrigatório.', $resposta->corpo);
    }

    public function testExcluirRemoveOSujeitoERedirecionaParaLista(): void
    {
        $controller = new SujeitosController(new HttpClientStub());

        $resposta = $controller->excluir(Request::criar('POST', '/sujeitos/sub-001/excluir')->comRouteParams(['id' => 'sub-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringNotContainsString('Sujeito A', $resposta->corpo);
    }
}
