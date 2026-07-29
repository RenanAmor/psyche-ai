<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Http;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Http\Request;

final class RequestTest extends TestCase
{
    public function testNormalizaMetodoEPath(): void
    {
        $request = Request::criar('get', '/sujeitos/');

        $this->assertSame('GET', $request->method);
        $this->assertSame('/sujeitos', $request->path);
    }

    public function testRaizPermaneceBarra(): void
    {
        $request = Request::criar('GET', '/');

        $this->assertSame('/', $request->path);
    }

    public function testQueryEInputComPadrao(): void
    {
        $request = Request::criar('GET', '/sujeitos', ['estado' => 'vazio'], ['nome' => 'Ana']);

        $this->assertSame('vazio', $request->query('estado'));
        $this->assertNull($request->query('inexistente'));
        $this->assertSame('padrao', $request->query('inexistente', 'padrao'));
        $this->assertSame('Ana', $request->input('nome'));
    }

    public function testComRouteParamsPreservaDemaisCampos(): void
    {
        $request = Request::criar('GET', '/sujeitos/42', ['a' => '1']);
        $comParams = $request->comRouteParams(['id' => '42']);

        $this->assertSame('42', $comParams->routeParam('id'));
        $this->assertSame('1', $comParams->query('a'));
        $this->assertSame($request->method, $comParams->method);
    }
}
