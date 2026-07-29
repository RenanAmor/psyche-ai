<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Components;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Components\AlertComponent;

final class AlertComponentTest extends TestCase
{
    public function testRenderizaComTipoValido(): void
    {
        $html = AlertComponent::render('Falha de comunicação.', 'erro');

        $this->assertStringContainsString('alerta alerta-erro', $html);
        $this->assertStringContainsString('Falha de comunicação.', $html);
        $this->assertStringContainsString('role="alert"', $html);
    }

    public function testCaiParaInfoQuandoTipoInvalido(): void
    {
        $html = AlertComponent::render('mensagem', 'tipo-invalido');

        $this->assertStringContainsString('alerta-info', $html);
    }
}
