<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Components;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Components\Html;

final class HtmlTest extends TestCase
{
    public function testEscapaCaracteresEspeciais(): void
    {
        $this->assertSame('&lt;b&gt;&amp;&lt;/b&gt;', Html::e('<b>&</b>'));
    }

    public function testAceitaIntEFloat(): void
    {
        $this->assertSame('3', Html::e(3));
        $this->assertSame('1.5', Html::e(1.5));
    }
}
