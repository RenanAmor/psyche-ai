<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Components;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Components\LoadingIndicatorComponent;

final class LoadingIndicatorComponentTest extends TestCase
{
    public function testRenderizaRotuloPadrao(): void
    {
        $html = LoadingIndicatorComponent::render();

        $this->assertStringContainsString('Carregando...', $html);
        $this->assertStringContainsString('role="status"', $html);
    }

    public function testRenderizaRotuloPersonalizado(): void
    {
        $html = LoadingIndicatorComponent::render('Carregando Sujeitos...');

        $this->assertStringContainsString('Carregando Sujeitos...', $html);
    }
}
