<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\ConversaController;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Tests\Support\HttpClientStub;
use PsycheAI\Tests\Support\MensagemHttpClientFake;

final class ConversaControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testIniciarCriaSujeitoESessaoAutomaticamenteQuandoNaoHaConversaAtiva(): void
    {
        $controller = new ConversaController(new HttpClientStub());

        $resposta = $controller->iniciar(Request::criar('GET', '/conversa'));

        $this->assertSame(200, $resposta->status);
        $this->assertArrayHasKey('psyche_conversa_sessao_id', $_SESSION);
        $this->assertNotSame('', $_SESSION['psyche_conversa_sessao_id']);
    }

    public function testIniciarReutilizaAConversaJaAtivaEmVezDeCriarOutra(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'sessao-fixa';

        $controller = new ConversaController(new HttpClientStub());
        $resposta = $controller->iniciar(Request::criar('GET', '/conversa'));

        $this->assertSame(200, $resposta->status);
        $this->assertSame('sessao-fixa', $_SESSION['psyche_conversa_sessao_id']);
    }

    public function testIniciarExibePaginaDeErroSemQuebrarQuandoAApiEstaIndisponivel(): void
    {
        $controller = new ConversaController(HttpClientStub::comFalha(ErrorType::COMUNICACAO));

        $resposta = $controller->iniciar(Request::criar('GET', '/conversa'));

        $this->assertSame(502, $resposta->status);
        $this->assertStringContainsString('Falha de comunicação', $resposta->corpo);
    }

    public function testEnviarComConteudoVazioReexibeAConversaComAlertaSemQuebrar(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'sessao-fixa';

        $controller = new ConversaController(new HttpClientStub());
        $resposta = $controller->enviar(Request::criar('POST', '/conversa/enviar', [], ['conteudo' => '   ']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('A mensagem não pode ser vazia.', $resposta->corpo);
    }

    public function testEnviarComSucessoExibeOHistoricoAtualizadoDaMesmaSessao(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'ses-fixa';

        $fake = new MensagemHttpClientFake([
            'events' => [
                ['id' => 'evt-1', 'conteudo' => 'Estou ansioso hoje.', 'posicao' => 0, 'sessaoId' => 'ses-fixa'],
                ['id' => 'evt-2', 'conteudo' => 'Recebi sua mensagem. Continue falando livremente.', 'posicao' => 1, 'sessaoId' => 'ses-fixa'],
            ],
        ]);

        $controller = new ConversaController($fake);
        $resposta = $controller->enviar(Request::criar('POST', '/conversa/enviar', [], ['conteudo' => 'Estou ansioso hoje.']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Estou ansioso hoje.', $resposta->corpo);
        $this->assertStringContainsString('Recebi sua mensagem. Continue falando livremente.', $resposta->corpo);
        $this->assertSame('ses-fixa', $_SESSION['psyche_conversa_sessao_id']);
    }

    public function testEnviarComSessaoInexistenteRecuperaAutomaticamenteComUmaNovaSessao(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'sessao-antiga';

        $fake = new MensagemHttpClientFake(['events' => []], ErrorType::NAO_ENCONTRADO);
        $controller = new ConversaController($fake);

        $resposta = $controller->enviar(Request::criar('POST', '/conversa/enviar', [], ['conteudo' => 'Continuando a conversa.']));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('não estava mais disponível', $resposta->corpo);
        $this->assertNotSame('sessao-antiga', $_SESSION['psyche_conversa_sessao_id']);
    }

    public function testEnviarComFalhaDeValidacaoReexibeAConversaComAlertaSemQuebrar(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'ses-1';

        $fake = new MensagemHttpClientFake(['events' => []], ErrorType::VALIDACAO);
        $controller = new ConversaController($fake);

        $resposta = $controller->enviar(
            Request::criar('POST', '/conversa/enviar', [], ['conteudo' => 'Mensagem de teste único 12345'])
        );

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Corrija os campos indicados', $resposta->corpo);
        $this->assertStringContainsString('Mensagem de teste único 12345', $resposta->corpo);
        $this->assertSame('ses-1', $_SESSION['psyche_conversa_sessao_id']);
    }
}
