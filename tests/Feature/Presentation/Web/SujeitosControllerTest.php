<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Client\MockApiHttpClient;
use PsycheAI\Presentation\Web\Controllers\SujeitosController;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Http\Request;

final class SujeitosControllerTest extends TestCase
{
    public function testListaSujeitosMockados(): void
    {
        $controller = new SujeitosController(new MockApiHttpClient());

        $resposta = $controller->index(Request::criar('GET', '/sujeitos'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Sujeito A', $resposta->corpo);
        $this->assertStringContainsString('<table', $resposta->corpo);
    }

    public function testEstadoVazioQuandoNaoHaSujeitos(): void
    {
        $controller = new SujeitosController(new MockApiHttpClient(['sujeitos' => []]));

        $resposta = $controller->index(Request::criar('GET', '/sujeitos'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('estado-vazio', $resposta->corpo);
        $this->assertStringContainsString('Nenhum sujeito cadastrado.', $resposta->corpo);
    }

    public function testEstadoDeCarregamento(): void
    {
        $controller = new SujeitosController(new MockApiHttpClient());

        $resposta = $controller->index(Request::criar('GET', '/sujeitos', ['estado' => 'carregando']));

        $this->assertStringContainsString('indicador-carregamento', $resposta->corpo);
    }

    public function testFalhaNaoEncontradoParaRecursoDesconhecido(): void
    {
        $controller = new SujeitosController(new MockApiHttpClient(['outro' => []]));

        $resposta = $controller->index(Request::criar('GET', '/sujeitos'));

        $this->assertSame(404, $resposta->status);
        $this->assertStringContainsString('Recurso não encontrado', $resposta->corpo);
    }

    public function testFalhaInternaExibePaginaDeErro(): void
    {
        $controller = new SujeitosController(MockApiHttpClient::comFalha(ErrorType::INTERNO));

        $resposta = $controller->index(Request::criar('GET', '/sujeitos'));

        $this->assertSame(500, $resposta->status);
        $this->assertStringContainsString('Erro interno', $resposta->corpo);
    }

    public function testNovoExibeFormularioVazio(): void
    {
        $controller = new SujeitosController(new MockApiHttpClient());

        $resposta = $controller->novo(Request::criar('GET', '/sujeitos/novo'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
        $this->assertStringNotContainsString('mensagem-erro', $resposta->corpo);
    }

    public function testStoreComNomeVazioReexibeFormularioComErroDeValidacao(): void
    {
        $controller = new SujeitosController(new MockApiHttpClient());

        $resposta = $controller->store(Request::criar('POST', '/sujeitos', [], ['nome' => '  ']));

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('O campo nome é obrigatório.', $resposta->corpo);
    }

    public function testStoreComNomeValidoRedirecionaParaListaAtualizada(): void
    {
        $controller = new SujeitosController(new MockApiHttpClient());

        $resposta = $controller->store(Request::criar('POST', '/sujeitos', [], ['nome' => 'Nova Pessoa']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<table', $resposta->corpo);
    }
}
