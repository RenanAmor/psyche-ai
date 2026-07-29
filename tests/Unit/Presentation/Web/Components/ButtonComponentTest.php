<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Components;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Components\ButtonComponent;

final class ButtonComponentTest extends TestCase
{
    public function testLinkRenderizaAncoraComVarianteValida(): void
    {
        $html = ButtonComponent::link('Novo Sujeito', '/sujeitos/novo', 'secundario');

        $this->assertStringContainsString('<a class="botao botao-secundario" href="/sujeitos/novo">Novo Sujeito</a>', $html);
    }

    public function testLinkCaiParaVariantePrimarioQuandoInvalida(): void
    {
        $html = ButtonComponent::link('Salvar', '/x', 'inexistente');

        $this->assertStringContainsString('botao-primario', $html);
    }

    public function testSubmitRenderizaBotaoDeEnvio(): void
    {
        $html = ButtonComponent::submit('Salvar', 'perigo');

        $this->assertStringContainsString('<button type="submit" class="botao botao-perigo">Salvar</button>', $html);
    }
}
