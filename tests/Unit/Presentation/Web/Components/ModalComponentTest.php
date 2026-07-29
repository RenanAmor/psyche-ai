<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Components;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Components\ModalComponent;

final class ModalComponentTest extends TestCase
{
    public function testRenderizaTituloCorpoEAcoes(): void
    {
        $html = ModalComponent::render('confirmar-exclusao', 'Confirmar exclusão', 'Tem certeza?', 'Excluir');

        $this->assertStringContainsString('id="confirmar-exclusao"', $html);
        $this->assertStringContainsString('modal-titulo">Confirmar exclusão<', $html);
        $this->assertStringContainsString('modal-corpo">Tem certeza?<', $html);
        $this->assertStringContainsString('>Excluir<', $html);
        $this->assertStringContainsString('Cancelar', $html);
        $this->assertStringContainsString('hidden', $html);
    }
}
