<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Security;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Security\PortaoDeParticipante;

final class PortaoDeParticipanteTest extends TestCase
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
        $this->assertFalse(PortaoDeParticipante::estaAutenticado());
        $this->assertNull(PortaoDeParticipante::participanteId());
    }

    public function testAbrirSessaoMarcaComoAutenticadoEGuardaOId(): void
    {
        PortaoDeParticipante::abrirSessao('participante-1');

        $this->assertTrue(PortaoDeParticipante::estaAutenticado());
        $this->assertSame('participante-1', PortaoDeParticipante::participanteId());
    }

    public function testSairRemoveAAutenticacaoDaSessao(): void
    {
        PortaoDeParticipante::abrirSessao('participante-1');
        PortaoDeParticipante::sair();

        $this->assertFalse(PortaoDeParticipante::estaAutenticado());
        $this->assertNull(PortaoDeParticipante::participanteId());
    }

    public function testNaoInterfereNaSessaoDoAnalista(): void
    {
        PortaoDeParticipante::abrirSessao('participante-1');

        $this->assertArrayNotHasKey('psyche_analista_autenticado', $_SESSION);
        $this->assertArrayNotHasKey('psyche_analista_id', $_SESSION);
    }

    public function testProtegerDelegaParaOHandlerQuandoAutenticado(): void
    {
        PortaoDeParticipante::abrirSessao('participante-1');

        $handler = static fn (Request $request): Response => Response::ok('conteúdo protegido');
        $protegido = PortaoDeParticipante::proteger($handler);

        $resposta = $protegido(Request::criar('GET', '/conversa'));

        $this->assertSame(200, $resposta->status);
        $this->assertSame('conteúdo protegido', $resposta->corpo);
    }

    public function testProtegerRedirecionaParaEntrarQuandoNaoAutenticado(): void
    {
        $handler = static fn (Request $request): Response => Response::ok('nunca deveria aparecer');
        $protegido = PortaoDeParticipante::proteger($handler);

        $resposta = $protegido(Request::criar('GET', '/conversa'));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/participante/entrar', $resposta->headers['Location']);
    }
}
