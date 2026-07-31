<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Http;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Http\BasePath;

final class BasePathTest extends TestCase
{
    protected function tearDown(): void
    {
        BasePath::definir('');
    }

    public function testSemPrefixoDefinidoUrlDevolveOCaminhoComoEsta(): void
    {
        $this->assertSame('', BasePath::valor());
        $this->assertSame('/conversa', BasePath::url('/conversa'));
    }

    public function testComPrefixoDefinidoUrlAntepoeOPrefixo(): void
    {
        BasePath::definir('/psycheai');

        $this->assertSame('/psycheai', BasePath::valor());
        $this->assertSame('/psycheai/conversa', BasePath::url('/conversa'));
    }

    public function testDefinirRemoveBarraFinalDoPrefixo(): void
    {
        BasePath::definir('/psycheai/');

        $this->assertSame('/psycheai', BasePath::valor());
        $this->assertSame('/psycheai/conversa', BasePath::url('/conversa'));
    }

    public function testUrlDaRaizComPrefixoDevolveOPrefixoComBarra(): void
    {
        BasePath::definir('/psycheai');

        $this->assertSame('/psycheai/', BasePath::url('/'));
    }
}
