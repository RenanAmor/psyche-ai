<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Components;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Components\FormComponent;

final class FormComponentTest extends TestCase
{
    public function testRenderizaCamposEBotaoDeSubmit(): void
    {
        $html = FormComponent::render('/sujeitos', 'POST', [
            ['nome' => 'nome', 'rotulo' => 'Nome', 'valor' => 'Ana'],
        ], 'Salvar');

        $this->assertStringContainsString('<form class="formulario" action="/sujeitos" method="POST">', $html);
        $this->assertStringContainsString('<label for="nome">Nome</label>', $html);
        $this->assertStringContainsString('value="Ana"', $html);
        $this->assertStringContainsString('botao-primario', $html);
        $this->assertStringNotContainsString('mensagem-erro', $html);
    }

    public function testRenderizaMensagemDeErroQuandoCampoFalha(): void
    {
        $html = FormComponent::render('/sujeitos', 'POST', [
            ['nome' => 'nome', 'rotulo' => 'Nome', 'erro' => 'O campo nome é obrigatório.'],
        ]);

        $this->assertStringContainsString('campo-com-erro', $html);
        $this->assertStringContainsString('O campo nome é obrigatório.', $html);
    }
}
