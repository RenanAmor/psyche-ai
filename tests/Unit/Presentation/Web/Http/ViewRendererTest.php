<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Http;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Http\ViewRenderer;
use PsycheAI\Presentation\Web\ViewModels\DashboardViewModel;
use RuntimeException;

final class ViewRendererTest extends TestCase
{
    public function testRenderizaViewComVariaveisExtraidas(): void
    {
        $diretorio = sys_get_temp_dir() . '/psyche-ai-views-' . uniqid('', true);
        mkdir($diretorio);
        file_put_contents($diretorio . '/saudacao.php', '<?php /** @var string $nome */ ?>Olá, <?= $nome ?>!');

        $renderer = new ViewRenderer($diretorio);
        $html = $renderer->render('saudacao', ['nome' => 'Ana']);

        $this->assertSame('Olá, Ana!', $html);

        unlink($diretorio . '/saudacao.php');
        rmdir($diretorio);
    }

    public function testLancaExcecaoParaViewInexistente(): void
    {
        $renderer = new ViewRenderer(sys_get_temp_dir());

        $this->expectException(RuntimeException::class);

        $renderer->render('nao-existe-' . uniqid('', true));
    }

    public function testRenderComLayoutEncaixaConteudoNoLayoutPrincipal(): void
    {
        $renderer = new ViewRenderer();

        $html = $renderer->renderComLayout('dashboard', [
            'dashboard' => DashboardViewModel::fromListas([], [], [], [], []),
        ], 'Dashboard', '/');

        $this->assertStringContainsString('Psyche AI', $html);
        $this->assertStringContainsString('Dashboard', $html);
        $this->assertStringContainsString('barra-lateral', $html);
    }
}
