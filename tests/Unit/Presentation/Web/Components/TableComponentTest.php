<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Components;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Components\TableComponent;

final class TableComponentTest extends TestCase
{
    public function testRenderizaCabecalhoELinhas(): void
    {
        $html = TableComponent::render(
            ['id' => 'ID', 'nome' => 'Nome'],
            [['id' => '1', 'nome' => 'Ana'], ['id' => '2', 'nome' => 'Bia']]
        );

        $this->assertStringContainsString('<table', $html);
        $this->assertStringContainsString('<th>ID</th>', $html);
        $this->assertStringContainsString('<th>Nome</th>', $html);
        $this->assertStringContainsString('<td>Ana</td>', $html);
        $this->assertStringContainsString('<td>Bia</td>', $html);
    }

    public function testEscapaConteudoDasCelulas(): void
    {
        $html = TableComponent::render(['nome' => 'Nome'], [['nome' => '<script>alert(1)</script>']]);

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function testDevolveEstadoVazioQuandoNaoHaLinhas(): void
    {
        $html = TableComponent::render(['id' => 'ID'], [], 'Nenhum item.');

        $this->assertStringNotContainsString('<table', $html);
        $this->assertStringContainsString('estado-vazio', $html);
        $this->assertStringContainsString('Nenhum item.', $html);
    }
}
