<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Http;

use PsycheAI\Presentation\Web\Navigation\NavigationMenu;

/**
 * Renderiza um arquivo de view PHP isolado em seu próprio escopo de
 * variáveis e, opcionalmente, encaixa o resultado dentro do layout
 * principal (header + sidebar + conteúdo). Nenhuma view acessa
 * Application/Domain — recebe apenas dados já prontos (ViewModels,
 * arrays, componentes já renderizados em string).
 */
final class ViewRenderer
{
    private string $viewsPath;

    public function __construct(?string $viewsPath = null)
    {
        $this->viewsPath = $viewsPath ?? (__DIR__ . '/../Views');
    }

    /**
     * @param array<string, mixed> $dados
     */
    public function render(string $view, array $dados = []): string
    {
        return $this->renderArquivo($view, $dados);
    }

    /**
     * @param array<string, mixed> $dados
     * @param string $layout Nome do arquivo de layout em Views/ (Sprint
     *        32) — 'layout' (padrão, chrome do Analista com sidebar) ou
     *        'layout-eco' (superfície do Sujeito, sem NavigationMenu).
     */
    public function renderComLayout(
        string $view,
        array $dados,
        string $tituloPagina,
        string $rotaAtiva,
        string $layout = 'layout'
    ): string {
        $conteudo = $this->renderArquivo($view, $dados);

        return $this->renderArquivo($layout, [
            'tituloPagina' => $tituloPagina,
            'rotaAtiva' => $rotaAtiva,
            'itensNavegacao' => NavigationMenu::itens(),
            'conteudo' => $conteudo,
        ]);
    }

    /**
     * @param array<string, mixed> $dados
     */
    private function renderArquivo(string $view, array $dados): string
    {
        $caminho = $this->viewsPath . '/' . $view . '.php';

        if (!is_file($caminho)) {
            throw new \RuntimeException(sprintf('View "%s" não encontrada em "%s".', $view, $caminho));
        }

        $renderizar = static function (string $__caminho, array $__dados): string {
            extract($__dados, EXTR_SKIP);
            ob_start();
            include $__caminho;

            return (string) ob_get_clean();
        };

        return $renderizar($caminho, $dados);
    }
}
