<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\AutenticacaoParticipanteController;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Security\PortaoDeParticipante;
use PsycheAI\Tests\Support\HttpClientStub;

final class AutenticacaoParticipanteControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    public function testEntrarExibeOFormulario(): void
    {
        $controller = new AutenticacaoParticipanteController(new HttpClientStub());

        $resposta = $controller->entrar(Request::criar('GET', '/participante/entrar'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
        $this->assertStringContainsString('email', $resposta->corpo);
        $this->assertStringContainsString('senha', $resposta->corpo);
    }

    public function testAutenticarComCredenciaisCorretasRedirecionaParaAConversa(): void
    {
        $controller = new AutenticacaoParticipanteController(new HttpClientStub());

        $resposta = $controller->autenticar(Request::criar('POST', '/participante/entrar', [], [
            'email' => HttpClientStub::PARTICIPANTE_EMAIL_PADRAO,
            'senha' => HttpClientStub::PARTICIPANTE_SENHA_PADRAO,
        ]));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/conversa', $resposta->headers['Location']);
        $this->assertTrue(PortaoDeParticipante::estaAutenticado());
        $this->assertSame(HttpClientStub::PARTICIPANTE_ID_PADRAO, PortaoDeParticipante::participanteId());
    }

    public function testAutenticarComSenhaIncorretaReexibeOFormularioComErro(): void
    {
        $controller = new AutenticacaoParticipanteController(new HttpClientStub());

        $resposta = $controller->autenticar(Request::criar('POST', '/participante/entrar', [], [
            'email' => HttpClientStub::PARTICIPANTE_EMAIL_PADRAO,
            'senha' => 'errada',
        ]));

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('E-mail ou senha inválidos', $resposta->corpo);
        $this->assertFalse(PortaoDeParticipante::estaAutenticado());
    }

    public function testSairDesautenticaERedirecionaParaEntrar(): void
    {
        PortaoDeParticipante::abrirSessao(HttpClientStub::PARTICIPANTE_ID_PADRAO);
        $controller = new AutenticacaoParticipanteController(new HttpClientStub());

        $resposta = $controller->sair(Request::criar('POST', '/participante/sair'));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/participante/entrar', $resposta->headers['Location']);
        $this->assertFalse(PortaoDeParticipante::estaAutenticado());
    }
}
