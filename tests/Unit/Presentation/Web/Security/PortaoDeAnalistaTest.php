<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Security;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Security\PortaoDeAnalista;

final class PortaoDeAnalistaTest extends TestCase
{
    private const SENHA = 'segredo-do-analista';

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

    public function testNaoEstaAutenticadoPorPadrao(): void
    {
        $this->assertFalse(PortaoDeAnalista::estaAutenticado());
    }

    public function testAutenticarComSenhaCorretaMarcaSessaoComoAutenticada(): void
    {
        $resultado = PortaoDeAnalista::autenticar(self::SENHA);

        $this->assertTrue($resultado);
        $this->assertTrue(PortaoDeAnalista::estaAutenticado());
    }

    public function testAutenticarComSenhaIncorretaNaoMarcaSessao(): void
    {
        $resultado = PortaoDeAnalista::autenticar('senha-errada');

        $this->assertFalse($resultado);
        $this->assertFalse(PortaoDeAnalista::estaAutenticado());
    }

    public function testAutenticarSemSenhaConfiguradaNoAmbienteFalhaSempre(): void
    {
        putenv('PSYCHEAI_SENHA_ANALISTA');

        $this->assertFalse(PortaoDeAnalista::autenticar(''));
        $this->assertFalse(PortaoDeAnalista::autenticar(self::SENHA));
    }

    public function testSairRemoveAAutenticacaoDaSessao(): void
    {
        PortaoDeAnalista::autenticar(self::SENHA);
        PortaoDeAnalista::sair();

        $this->assertFalse(PortaoDeAnalista::estaAutenticado());
    }

    public function testProtegerDelegaParaOHandlerQuandoAutenticado(): void
    {
        PortaoDeAnalista::autenticar(self::SENHA);

        $handler = static fn (Request $request): Response => Response::ok('conteúdo protegido');
        $protegido = PortaoDeAnalista::proteger($handler);

        $resposta = $protegido(Request::criar('GET', '/'));

        $this->assertSame(200, $resposta->status);
        $this->assertSame('conteúdo protegido', $resposta->corpo);
    }

    public function testProtegerRedirecionaParaEntrarQuandoNaoAutenticado(): void
    {
        $handler = static fn (Request $request): Response => Response::ok('nunca deveria aparecer');
        $protegido = PortaoDeAnalista::proteger($handler);

        $resposta = $protegido(Request::criar('GET', '/'));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/entrar', $resposta->headers['Location']);
    }
}
