<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Components;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Components\CardComponent;

final class CardComponentTest extends TestCase
{
    public function testRenderizaTituloValorEDescricao(): void
    {
        $html = CardComponent::render('Sujeitos', 3, 'Total cadastrado', 'user');

        $this->assertStringContainsString('cartao-titulo">Sujeitos<', $html);
        $this->assertStringContainsString('cartao-valor">3<', $html);
        $this->assertStringContainsString('cartao-descricao">Total cadastrado<', $html);
        $this->assertStringContainsString('data-icone="user"', $html);
    }
}
