<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\AutenticacaoAnalistaController;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Security\PortaoDeAnalista;

final class AutenticacaoAnalistaControllerTest extends TestCase
{
    private const SENHA = 'senha-correta';

    protected function setUp(): void
    {
        $_SESSION = [];
        putenv('PSYCHEAI_SENHA_ANALISTA=' . self::SENHA);
    }

    protected function tearDown(): void
    {
        putenv('PSYCHEAI_SENHA_ANALISTA');
        $_SESSION = [];
    }

    public function testEntrarExibeOFormulario(): void
    {
        $controller = new AutenticacaoAnalistaController();

        $resposta = $controller->entrar(Request::criar('GET', '/entrar'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
        $this->assertStringContainsString('senha', $resposta->corpo);
    }

    public function testAutenticarComSenhaCorretaRedirecionaParaODashboard(): void
    {
        $controller = new AutenticacaoAnalistaController();

        $resposta = $controller->autenticar(Request::criar('POST', '/entrar', [], ['senha' => self::SENHA]));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/', $resposta->headers['Location']);
        $this->assertTrue(PortaoDeAnalista::estaAutenticado());
    }

    public function testAutenticarComSenhaIncorretaReexibeOFormularioComErro(): void
    {
        $controller = new AutenticacaoAnalistaController();

        $resposta = $controller->autenticar(Request::criar('POST', '/entrar', [], ['senha' => 'errada']));

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('Senha inválida', $resposta->corpo);
        $this->assertFalse(PortaoDeAnalista::estaAutenticado());
    }

    public function testSairDesautenticaERedirecionaParaEntrar(): void
    {
        PortaoDeAnalista::autenticar(self::SENHA);
        $controller = new AutenticacaoAnalistaController();

        $resposta = $controller->sair(Request::criar('POST', '/sair'));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/entrar', $resposta->headers['Location']);
        $this->assertFalse(PortaoDeAnalista::estaAutenticado());
    }
}
