<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\ConversaController;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Security\PortaoDeParticipante;
use PsycheAI\Tests\Support\HttpClientStub;
use PsycheAI\Tests\Support\MensagemHttpClientFake;

final class ConversaControllerTest extends TestCase
{
    private const PARTICIPANTE_FIXO = 'participante-fixo';

    protected function setUp(): void
    {
        $_SESSION = [];
        $_COOKIE = [];

        PortaoDeParticipante::abrirSessao(self::PARTICIPANTE_FIXO);
    }

    public function testIniciarCriaSujeitoESessaoAutomaticamenteQuandoNaoHaConversaAtiva(): void
    {
        $controller = new ConversaController(new HttpClientStub());

        $resposta = $controller->iniciar(Request::criar('GET', '/conversa'));

        $this->assertSame(200, $resposta->status);
        $this->assertArrayHasKey('psyche_conversa_sessao_id', $_SESSION);
        $this->assertNotSame('', $_SESSION['psyche_conversa_sessao_id']);
    }

    public function testIniciarUsaOIdDoParticipanteAutenticadoParaCriarOSujeito(): void
    {
        $stub = new HttpClientStub();
        $controller = new ConversaController($stub);

        $resposta = $controller->iniciar(Request::criar('GET', '/conversa'));

        $this->assertSame(200, $resposta->status);

        $sujeito = $stub->get('subjects/' . self::PARTICIPANTE_FIXO);
        $this->assertTrue($sujeito->sucesso);
        $this->assertSame(self::PARTICIPANTE_FIXO, $sujeito->dados['id']);
    }

    public function testDoisParticipantesAutenticadosDiferentesGeramSujeitosDiferentes(): void
    {
        $stubUm = new HttpClientStub();
        (new ConversaController($stubUm))->iniciar(Request::criar('GET', '/conversa'));

        $_SESSION = [];
        $_COOKIE = [];
        PortaoDeParticipante::abrirSessao('outro-participante');

        $stubDois = new HttpClientStub();
        (new ConversaController($stubDois))->iniciar(Request::criar('GET', '/conversa'));

        $this->assertTrue($stubUm->get('subjects/' . self::PARTICIPANTE_FIXO)->sucesso);
        $this->assertTrue($stubDois->get('subjects/outro-participante')->sucesso);
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
                ['id' => 'evt-1', 'conteudo' => 'Estou ansioso hoje.', 'posicao' => 0, 'sessaoId' => 'ses-fixa', 'locutor' => 'sujeito'],
                ['id' => 'evt-2', 'conteudo' => 'Recebi sua mensagem. Continue falando livremente.', 'posicao' => 1, 'sessaoId' => 'ses-fixa', 'locutor' => 'sistema'],
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

    public function testMensagensRetornaJsonComOFragmentoDeHistoricoAtualizado(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'ses-fixa';

        $fake = new MensagemHttpClientFake([
            'events' => [
                ['id' => 'evt-1', 'conteudo' => 'Estou ansioso hoje.', 'posicao' => 0, 'sessaoId' => 'ses-fixa', 'locutor' => 'sujeito'],
                ['id' => 'evt-2', 'conteudo' => 'Recebi sua mensagem. Continue falando livremente.', 'posicao' => 1, 'sessaoId' => 'ses-fixa', 'locutor' => 'sistema'],
            ],
        ]);

        $controller = new ConversaController($fake);
        $resposta = $controller->mensagens(Request::criar('POST', '/conversa/mensagens', [], ['conteudo' => 'Estou ansioso hoje.']));

        $this->assertSame(200, $resposta->status);
        $corpo = json_decode($resposta->corpo, true);

        $this->assertTrue($corpo['sucesso']);
        $this->assertSame('', $corpo['valorConteudo']);
        $this->assertStringContainsString('Estou ansioso hoje.', $corpo['html']);
        $this->assertStringContainsString('Recebi sua mensagem. Continue falando livremente.', $corpo['html']);
    }

    public function testMensagensComConteudoVazioRetornaJsonComAlertaDeErroSemQuebrar(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'sessao-fixa';

        $controller = new ConversaController(new HttpClientStub());
        $resposta = $controller->mensagens(Request::criar('POST', '/conversa/mensagens', [], ['conteudo' => '   ']));

        $this->assertSame(200, $resposta->status);
        $corpo = json_decode($resposta->corpo, true);

        $this->assertFalse($corpo['sucesso']);
        $this->assertStringContainsString('A mensagem não pode ser vazia.', $corpo['html']);
    }

    public function testMensagensComSessaoInexistenteRecuperaAutomaticamenteComUmaNovaSessao(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'sessao-antiga';

        $fake = new MensagemHttpClientFake(['events' => []], ErrorType::NAO_ENCONTRADO);
        $controller = new ConversaController($fake);

        $resposta = $controller->mensagens(
            Request::criar('POST', '/conversa/mensagens', [], ['conteudo' => 'Continuando a conversa.'])
        );

        $this->assertSame(200, $resposta->status);
        $corpo = json_decode($resposta->corpo, true);

        $this->assertTrue($corpo['sucesso']);
        $this->assertStringContainsString('não estava mais disponível', $corpo['html']);
        $this->assertNotSame('sessao-antiga', $_SESSION['psyche_conversa_sessao_id']);
    }

    public function testAudioComCorpoVazioRetorna400SemChamarAApi(): void
    {
        $controller = new ConversaController(HttpClientStub::comFalha(ErrorType::COMUNICACAO));

        $resposta = $controller->audio(Request::criar('POST', '/conversa/audio', [], [], ''));

        $this->assertSame(400, $resposta->status);
        $this->assertStringContainsString('vazia', $resposta->corpo);
    }

    public function testAudioComSucessoRepassaOBinarioParaASessaoAtiva(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'ses-fixa';

        $controller = new ConversaController(new HttpClientStub());
        $resposta = $controller->audio(Request::criar('POST', '/conversa/audio', [], [], 'bytes-do-audio'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('"sucesso":true', $resposta->corpo);
    }

    public function testAudioComFalhaDeComunicacaoRetorna502(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'ses-fixa';

        $controller = new ConversaController(HttpClientStub::comFalha(ErrorType::COMUNICACAO));
        $resposta = $controller->audio(Request::criar('POST', '/conversa/audio', [], [], 'bytes-do-audio'));

        $this->assertSame(502, $resposta->status);
        $this->assertStringContainsString('"sucesso":false', $resposta->corpo);
    }

    public function testMensagensAudioComCorpoVazioRetorna400SemChamarAApi(): void
    {
        $controller = new ConversaController(HttpClientStub::comFalha(ErrorType::COMUNICACAO));

        $resposta = $controller->mensagensAudio(Request::criar('POST', '/conversa/mensagens/audio', [], [], ''));

        $this->assertSame(400, $resposta->status);
        $this->assertStringContainsString('vazia', $resposta->corpo);
    }

    public function testMensagensAudioTranscreveEEnviaComoUmaMensagemDigitada(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'ses-fixa';

        $fake = new MensagemHttpClientFake(
            [
                'events' => [
                    ['id' => 'evt-1', 'conteudo' => 'Estou ansioso hoje.', 'posicao' => 0, 'sessaoId' => 'ses-fixa', 'locutor' => 'sujeito'],
                    ['id' => 'evt-2', 'conteudo' => 'Recebi sua mensagem. Continue falando livremente.', 'posicao' => 1, 'sessaoId' => 'ses-fixa', 'locutor' => 'sistema'],
                ],
            ],
            textoTranscricao: 'Estou ansioso hoje.'
        );

        $controller = new ConversaController($fake);
        $resposta = $controller->mensagensAudio(Request::criar('POST', '/conversa/mensagens/audio', [], [], 'bytes-do-turno'));

        $this->assertSame(200, $resposta->status);
        $corpo = json_decode($resposta->corpo, true);

        $this->assertTrue($corpo['sucesso']);
        $this->assertSame('Estou ansioso hoje.', $corpo['textoTranscrito']);
        $this->assertStringContainsString('/conversa/mensagens/evt-2/audio', $corpo['audioRespostaUrl']);
        $this->assertStringContainsString('Recebi sua mensagem. Continue falando livremente.', $corpo['html']);
    }

    public function testMensagensAudioSemFalaReconhecidaNaoCriaTurnoNenhum(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'ses-fixa';

        $fake = new MensagemHttpClientFake(['events' => []], textoTranscricao: '   ');

        $controller = new ConversaController($fake);
        $resposta = $controller->mensagensAudio(Request::criar('POST', '/conversa/mensagens/audio', [], [], 'bytes-de-silencio'));

        $this->assertSame(200, $resposta->status);
        $corpo = json_decode($resposta->corpo, true);

        $this->assertFalse($corpo['sucesso']);
        $this->assertStringContainsString('Não conseguimos identificar fala', $resposta->corpo);
        $this->assertNull($corpo['audioRespostaUrl']);
    }

    public function testMensagensAudioComFalhaNaTranscricaoRetorna502(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'ses-fixa';

        $controller = new ConversaController(HttpClientStub::comFalha(ErrorType::COMUNICACAO));
        $resposta = $controller->mensagensAudio(Request::criar('POST', '/conversa/mensagens/audio', [], [], 'bytes-do-turno'));

        $this->assertSame(502, $resposta->status);
        $this->assertStringContainsString('"sucesso":false', $resposta->corpo);
    }
}
