<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Http;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Http\Cors;
use PsycheAI\Presentation\Http\Request;

final class CorsTest extends TestCase
{
    public function testOrigemNaAllowlistRecebeCabecalhosDeCors(): void
    {
        $cors = new Cors(['https://ecopsy.online']);

        $cabecalhos = $cors->cabecalhosPara('https://ecopsy.online');

        self::assertSame('https://ecopsy.online', $cabecalhos['Access-Control-Allow-Origin']);
        self::assertSame('GET, POST, OPTIONS', $cabecalhos['Access-Control-Allow-Methods']);
        self::assertSame('Content-Type', $cabecalhos['Access-Control-Allow-Headers']);
    }

    public function testOrigemForaDaAllowlistNaoRecebeCabecalhoNenhum(): void
    {
        $cors = new Cors(['https://ecopsy.online']);

        self::assertSame([], $cors->cabecalhosPara('https://outro-site.com'));
    }

    public function testAllowlistVaziaNaoLiberaNenhumaOrigem(): void
    {
        $cors = new Cors([]);

        self::assertSame([], $cors->cabecalhosPara('https://ecopsy.online'));
    }

    public function testOrigemNulaNaoRecebeCabecalhoNenhum(): void
    {
        $cors = new Cors(['https://ecopsy.online']);

        self::assertSame([], $cors->cabecalhosPara(null));
    }

    public function testRequisicaoOptionsEhReconhecidaComoPreflight(): void
    {
        $cors = new Cors(['https://ecopsy.online']);

        self::assertTrue($cors->ehPreflight(Request::criar('OPTIONS', '/waitlist')));
        self::assertFalse($cors->ehPreflight(Request::criar('POST', '/waitlist')));
    }
}
