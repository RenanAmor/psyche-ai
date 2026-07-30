<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\ErrorController;
use PsycheAI\Presentation\Web\Http\Request;

final class ErrorControllerTest extends TestCase
{
    public function testComunicacao(): void
    {
        $resposta = (new ErrorController())->comunicacao(Request::criar('GET', '/erros/comunicacao'));

        $this->assertSame(502, $resposta->status);
        $this->assertStringContainsString('Falha de comunicação', $resposta->corpo);
    }

    public function testNaoEncontrado(): void
    {
        $resposta = (new ErrorController())->naoEncontrado(Request::criar('GET', '/rota-qualquer'));

        $this->assertSame(404, $resposta->status);
        $this->assertStringContainsString('Recurso não encontrado', $resposta->corpo);
    }

    public function testValidacao(): void
    {
        $resposta = (new ErrorController())->validacao(Request::criar('GET', '/erros/validacao'));

        $this->assertSame(422, $resposta->status);
        $this->assertStringContainsString('Dados inválidos', $resposta->corpo);
        $this->assertStringContainsString('nome', $resposta->corpo);
    }

    public function testInterno(): void
    {
        $resposta = (new ErrorController())->interno(Request::criar('GET', '/erros/interno'));

        $this->assertSame(500, $resposta->status);
        $this->assertStringContainsString('Erro interno', $resposta->corpo);
    }

    public function testTimeout(): void
    {
        $resposta = (new ErrorController())->timeout(Request::criar('GET', '/erros/timeout'));

        $this->assertSame(504, $resposta->status);
        $this->assertStringContainsString('Tempo de espera esgotado', $resposta->corpo);
    }
}
