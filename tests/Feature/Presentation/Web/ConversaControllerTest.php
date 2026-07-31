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
        $_COOKIE = [];
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

    public function testIniciarGeraUmCookieDePessoaQuandoNaoExisteAindaEOUsaParaCriarOSujeito(): void
    {
        $controller = new ConversaController(new HttpClientStub());

        $resposta = $controller->iniciar(Request::criar('GET', '/conversa'));

        $this->assertSame(200, $resposta->status);
        $this->assertArrayHasKey('psyche_pessoa_id', $_COOKIE);
        $this->assertNotSame('', $_COOKIE['psyche_pessoa_id']);
    }

    public function testIniciarReutilizaOCookieDePessoaJaExistenteEmVezDeGerarOutro(): void
    {
        $_COOKIE['psyche_pessoa_id'] = 'pessoa-fixa';

        $controller = new ConversaController(new HttpClientStub());
        $resposta = $controller->iniciar(Request::criar('GET', '/conversa'));

        $this->assertSame(200, $resposta->status);
        $this->assertSame('pessoa-fixa', $_COOKIE['psyche_pessoa_id']);
    }

    public function testDuasConversasIndependentesRecebemCookiesDePessoaDiferentes(): void
    {
        (new ConversaController(new HttpClientStub()))->iniciar(Request::criar('GET', '/conversa'));
        $primeiroPessoaId = $_COOKIE['psyche_pessoa_id'];

        $_SESSION = [];
        $_COOKIE = [];

        (new ConversaController(new HttpClientStub()))->iniciar(Request::criar('GET', '/conversa'));
        $segundoPessoaId = $_COOKIE['psyche_pessoa_id'];

        $this->assertNotSame($primeiroPessoaId, $segundoPessoaId);
    }

    public function testMensagensRetornaJsonComOFragmentoDeHistoricoAtualizado(): void
    {
        $_SESSION['psyche_conversa_sessao_id'] = 'ses-fixa';

        $fake = new MensagemHttpClientFake([
            'events' => [
                ['id' => 'evt-1', 'conteudo' => 'Estou ansioso hoje.', 'posicao' => 0, 'sessaoId' => 'ses-fixa'],
                ['id' => 'evt-2', 'conteudo' => 'Recebi sua mensagem. Continue falando livremente.', 'posicao' => 1, 'sessaoId' => 'ses-fixa'],
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

    public function testCadastroExibeOFormulario(): void
    {
        $controller = new ConversaController(new HttpClientStub());

        $resposta = $controller->cadastro(Request::criar('GET', '/conversa/cadastro'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
        $this->assertStringContainsString('email', $resposta->corpo);
        $this->assertStringContainsString('senha', $resposta->corpo);
    }

    public function testCadastrarLigaContaAoSujeitoDoCookieERedireciona(): void
    {
        $_COOKIE['psyche_pessoa_id'] = 'pessoa-fixa';

        $controller = new ConversaController(new HttpClientStub());
        $resposta = $controller->cadastrar(Request::criar('POST', '/conversa/cadastro', [], [
            'email' => 'sujeito@psyche.ai',
            'senha' => 'segredo',
        ]));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/conversa', $resposta->headers['Location']);
    }

    public function testCadastrarQuandoSujeitoJaTemContaReexibeFormularioComErro(): void
    {
        $_COOKIE['psyche_pessoa_id'] = 'pessoa-fixa';

        $fake = new HttpClientStub([
            'subjects' => [
                ['id' => 'pessoa-fixa', 'nome' => 'Visitante', 'email' => 'ja-cadastrado@psyche.ai'],
            ],
        ]);
        $controller = new ConversaController($fake);

        $resposta = $controller->cadastrar(Request::criar('POST', '/conversa/cadastro', [], [
            'email' => 'novo@psyche.ai',
            'senha' => 'segredo',
        ]));

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('Corrija os campos indicados', $resposta->corpo);
    }

    public function testEntrarExibeOFormulario(): void
    {
        $controller = new ConversaController(new HttpClientStub());

        $resposta = $controller->entrar(Request::criar('GET', '/conversa/entrar'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
    }

    public function testAutenticarComCredenciaisCorretasTrocaOCookieERedireciona(): void
    {
        $_COOKIE['psyche_pessoa_id'] = 'pessoa-anonima-anterior';
        $_SESSION['psyche_conversa_sessao_id'] = 'sessao-da-identidade-anterior';

        $controller = new ConversaController(new HttpClientStub());
        $resposta = $controller->autenticar(Request::criar('POST', '/conversa/entrar', [], [
            'email' => HttpClientStub::SUJEITO_EMAIL_PADRAO,
            'senha' => HttpClientStub::SUJEITO_SENHA_PADRAO,
        ]));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/conversa', $resposta->headers['Location']);
        $this->assertSame(HttpClientStub::SUJEITO_ID_PADRAO, $_COOKIE['psyche_pessoa_id']);
        $this->assertArrayNotHasKey('psyche_conversa_sessao_id', $_SESSION);
    }

    public function testAutenticarComCredenciaisIncorretasReexibeFormularioComErro(): void
    {
        $controller = new ConversaController(new HttpClientStub());
        $resposta = $controller->autenticar(Request::criar('POST', '/conversa/entrar', [], [
            'email' => HttpClientStub::SUJEITO_EMAIL_PADRAO,
            'senha' => 'errada',
        ]));

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('E-mail ou senha inválidos', $resposta->corpo);
    }

    public function testSairRemoveOCookieDePessoaERedireciona(): void
    {
        $_COOKIE['psyche_pessoa_id'] = 'pessoa-fixa';
        $_SESSION['psyche_conversa_sessao_id'] = 'sessao-fixa';

        $controller = new ConversaController(new HttpClientStub());
        $resposta = $controller->sair(Request::criar('POST', '/conversa/sair'));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/conversa', $resposta->headers['Location']);
        $this->assertArrayNotHasKey('psyche_pessoa_id', $_COOKIE);
        $this->assertArrayNotHasKey('psyche_conversa_sessao_id', $_SESSION);
    }
}
