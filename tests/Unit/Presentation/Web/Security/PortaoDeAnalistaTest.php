<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Security;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Security\PortaoDeAnalista;

final class PortaoDeAnalistaTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    public function testNaoEstaAutenticadoPorPadrao(): void
    {
        $this->assertFalse(PortaoDeAnalista::estaAutenticado());
        $this->assertNull(PortaoDeAnalista::analistaId());
    }

    public function testAbrirSessaoMarcaComoAutenticadoEGuardaOId(): void
    {
        PortaoDeAnalista::abrirSessao('analista-1');

        $this->assertTrue(PortaoDeAnalista::estaAutenticado());
        $this->assertSame('analista-1', PortaoDeAnalista::analistaId());
    }

    public function testSairRemoveAAutenticacaoDaSessao(): void
    {
        PortaoDeAnalista::abrirSessao('analista-1');
        PortaoDeAnalista::sair();

        $this->assertFalse(PortaoDeAnalista::estaAutenticado());
        $this->assertNull(PortaoDeAnalista::analistaId());
    }

    public function testProtegerDelegaParaOHandlerQuandoAutenticado(): void
    {
        PortaoDeAnalista::abrirSessao('analista-1');

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
