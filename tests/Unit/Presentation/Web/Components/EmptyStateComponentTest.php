<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Components;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Components\EmptyStateComponent;

final class EmptyStateComponentTest extends TestCase
{
    public function testRenderizaMensagemPadrao(): void
    {
        $html = EmptyStateComponent::render();

        $this->assertStringContainsString('Nenhum registro encontrado.', $html);
        $this->assertStringContainsString('estado-vazio', $html);
    }

    public function testRenderizaMensagemPersonalizada(): void
    {
        $html = EmptyStateComponent::render('Nenhum sujeito cadastrado.');

        $this->assertStringContainsString('Nenhum sujeito cadastrado.', $html);
    }
}
