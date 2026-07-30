<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Http;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Http\Response;

final class ResponseTest extends TestCase
{
    public function testFactoriesRetornamOsStatusCorretos(): void
    {
        $this->assertSame(200, Response::ok('x')->status);
        $this->assertSame(404, Response::naoEncontrado('x')->status);
        $this->assertSame(422, Response::erroValidacao('x')->status);
        $this->assertSame(502, Response::erroServico('x')->status);
        $this->assertSame(500, Response::erroInterno('x')->status);
    }

    public function testCorpoEHeaderPadraoHtml(): void
    {
        $response = Response::ok('<p>ola</p>');

        $this->assertSame('<p>ola</p>', $response->corpo);
        $this->assertSame('text/html; charset=utf-8', $response->headers['Content-Type']);
    }

    public function testRedirecionarDevolve302ComLocation(): void
    {
        $response = Response::redirecionar('/entrar');

        $this->assertSame(302, $response->status);
        $this->assertSame('/entrar', $response->headers['Location']);
        $this->assertSame('', $response->corpo);
    }
}
