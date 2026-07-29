<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Errors\ErrorViewModel;
use PsycheAI\Presentation\Web\Errors\ErrorViewModelFactory;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;

/**
 * Concentra a exibição dos quatro tipos de erro exigidos pela Sprint
 * 11A. As três primeiras ações abaixo existem para tornar cada estado
 * de erro navegável e testável mesmo sem uma falha real; naoEncontrado
 * também serve de handler padrão do Router para rotas inexistentes.
 */
final class ErrorController
{
    public function __construct(
        private readonly ViewRenderer $viewRenderer = new ViewRenderer()
    ) {
    }

    public static function renderizar(ErrorViewModel $erro, ViewRenderer $viewRenderer, string $rotaAtiva): Response
    {
        $html = $viewRenderer->renderComLayout('errors/error', ['erro' => $erro], $erro->titulo, $rotaAtiva);

        return new Response($html, $erro->statusHttp());
    }

    public function comunicacao(Request $request): Response
    {
        return self::renderizar(ErrorViewModelFactory::comunicacao('api'), $this->viewRenderer, $request->path);
    }

    public function naoEncontrado(Request $request): Response
    {
        return self::renderizar(ErrorViewModelFactory::naoEncontrado($request->path), $this->viewRenderer, $request->path);
    }

    public function validacao(Request $request): Response
    {
        $erro = ErrorViewModelFactory::validacao(['nome' => 'O campo nome é obrigatório.']);

        return self::renderizar($erro, $this->viewRenderer, $request->path);
    }

    public function interno(Request $request): Response
    {
        return self::renderizar(ErrorViewModelFactory::interno(), $this->viewRenderer, $request->path);
    }
}
