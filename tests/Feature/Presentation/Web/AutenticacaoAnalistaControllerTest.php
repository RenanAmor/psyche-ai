<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\AutenticacaoAnalistaController;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Security\PortaoDeAnalista;
use PsycheAI\Tests\Support\HttpClientStub;

final class AutenticacaoAnalistaControllerTest extends TestCase
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
        $controller = new AutenticacaoAnalistaController(new HttpClientStub());

        $resposta = $controller->entrar(Request::criar('GET', '/entrar'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
        $this->assertStringContainsString('email', $resposta->corpo);
        $this->assertStringContainsString('senha', $resposta->corpo);
    }

    public function testAutenticarComCredenciaisCorretasRedirecionaParaODashboard(): void
    {
        $controller = new AutenticacaoAnalistaController(new HttpClientStub());

        $resposta = $controller->autenticar(Request::criar('POST', '/entrar', [], [
            'email' => HttpClientStub::ANALISTA_EMAIL_PADRAO,
            'senha' => HttpClientStub::ANALISTA_SENHA_PADRAO,
        ]));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/', $resposta->headers['Location']);
        $this->assertTrue(PortaoDeAnalista::estaAutenticado());
        $this->assertSame(HttpClientStub::ANALISTA_ID_PADRAO, PortaoDeAnalista::analistaId());
    }

    public function testAutenticarComSenhaIncorretaReexibeOFormularioComErro(): void
    {
        $controller = new AutenticacaoAnalistaController(new HttpClientStub());

        $resposta = $controller->autenticar(Request::criar('POST', '/entrar', [], [
            'email' => HttpClientStub::ANALISTA_EMAIL_PADRAO,
            'senha' => 'errada',
        ]));

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('E-mail ou senha inválidos', $resposta->corpo);
        $this->assertFalse(PortaoDeAnalista::estaAutenticado());
    }

    public function testSairDesautenticaERedirecionaParaEntrar(): void
    {
        PortaoDeAnalista::abrirSessao(HttpClientStub::ANALISTA_ID_PADRAO);
        $controller = new AutenticacaoAnalistaController(new HttpClientStub());

        $resposta = $controller->sair(Request::criar('POST', '/sair'));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/entrar', $resposta->headers['Location']);
        $this->assertFalse(PortaoDeAnalista::estaAutenticado());
    }
}
