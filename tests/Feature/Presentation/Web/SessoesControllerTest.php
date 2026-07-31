<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\SessoesController;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Tests\Support\HttpClientStub;

final class SessoesControllerTest extends TestCase
{
    public function testListaSessoesMockadas(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->index(Request::criar('GET', '/sessoes'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('2026-06-02', $resposta->corpo);
    }

    public function testEstadoVazio(): void
    {
        $controller = new SessoesController(new HttpClientStub(['sessions' => []]));

        $resposta = $controller->index(Request::criar('GET', '/sessoes'));

        $this->assertStringContainsString('Nenhuma sessão registrada.', $resposta->corpo);
    }

    public function testEstadoDeCarregamento(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->index(Request::criar('GET', '/sessoes', ['estado' => 'carregando']));

        $this->assertStringContainsString('indicador-carregamento', $resposta->corpo);
    }

    public function testNovoExibeFormularioVazio(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->novo(Request::criar('GET', '/sessoes/novo'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
    }

    public function testStoreComCamposValidosRedirecionaParaListaAtualizada(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->store(Request::criar('POST', '/sessoes', [], [
            'id' => 'ses-999',
            'sujeitoId' => 'sub-001',
            'data' => '2026-07-01',
        ]));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<table', $resposta->corpo);
    }

    public function testStoreComCamposAusentesReexibeFormularioComErros(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->store(Request::criar('POST', '/sessoes', [], ['id' => 'ses-999']));

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('O campo sujeitoId é obrigatório.', $resposta->corpo);
        $this->assertStringContainsString('O campo data é obrigatório.', $resposta->corpo);
    }

    public function testMostrarExibeODetalheDaSessao(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->mostrar(Request::criar('GET', '/sessoes/ses-001')->comRouteParams(['id' => 'ses-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('2026-06-02', $resposta->corpo);
    }

    public function testMostrarComIdInexistenteExibePaginaDeErro(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->mostrar(Request::criar('GET', '/sessoes/inexistente')->comRouteParams(['id' => 'inexistente']));

        $this->assertSame(404, $resposta->status);
    }

    public function testEditarExibeFormularioPreenchido(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->editar(Request::criar('GET', '/sessoes/ses-001/editar')->comRouteParams(['id' => 'ses-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('value="2026-06-02"', $resposta->corpo);
    }

    public function testAtualizarComDataValidaRedirecionaParaListaAtualizada(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->atualizar(
            Request::criar('POST', '/sessoes/ses-001', [], ['data' => '2026-08-01'])->comRouteParams(['id' => 'ses-001'])
        );

        $this->assertSame(200, $resposta->status);
    }

    public function testAtualizarComDataVaziaReexibeFormularioComErro(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->atualizar(
            Request::criar('POST', '/sessoes/ses-001', [], ['data' => ''])->comRouteParams(['id' => 'ses-001'])
        );

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('O campo data é obrigatório.', $resposta->corpo);
    }

    public function testExcluirRemoveASessaoERedirecionaParaLista(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->excluir(Request::criar('POST', '/sessoes/ses-001/excluir')->comRouteParams(['id' => 'ses-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringNotContainsString('2026-06-02', $resposta->corpo);
    }

    public function testMostrarExibeOPlayerDeAudioApontandoParaARotaWeb(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->mostrar(Request::criar('GET', '/sessoes/ses-001')->comRouteParams(['id' => 'ses-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('/sessoes/ses-001/audio', $resposta->corpo);
    }

    public function testAudioDevolveOsBytesQuandoAGravacaoExiste(): void
    {
        $httpClient = new HttpClientStub();
        $httpClient->postBinario('sessions/ses-001/audio', 'bytes-da-gravacao');

        $controller = new SessoesController($httpClient);
        $resposta = $controller->audio(Request::criar('GET', '/sessoes/ses-001/audio')->comRouteParams(['id' => 'ses-001']));

        $this->assertSame(200, $resposta->status);
        $this->assertSame('bytes-da-gravacao', $resposta->corpo);
        $this->assertSame('audio/webm', $resposta->headers['Content-Type']);
    }

    public function testAudioRetorna404QuandoNaoHaGravacao(): void
    {
        $controller = new SessoesController(new HttpClientStub());

        $resposta = $controller->audio(Request::criar('GET', '/sessoes/ses-001/audio')->comRouteParams(['id' => 'ses-001']));

        $this->assertSame(404, $resposta->status);
    }
}
