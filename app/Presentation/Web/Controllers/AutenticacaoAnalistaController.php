<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;
use PsycheAI\Presentation\Web\Security\PortaoDeAnalista;

/**
 * Tela de entrada/saída do Portão do Analista (revisão pós-Sprint 16).
 * Não protegida por `PortaoDeAnalista::proteger()` — é justamente a
 * porta de acesso a ele.
 */
final class AutenticacaoAnalistaController
{
    private const ROTA = '/entrar';

    public function __construct(
        private readonly ViewRenderer $viewRenderer = new ViewRenderer()
    ) {
    }

    public function entrar(Request $request): Response
    {
        return $this->renderizarFormulario();
    }

    public function autenticar(Request $request): Response
    {
        $senha = (string) $request->input('senha', '');

        if (!PortaoDeAnalista::autenticar($senha)) {
            return Response::erroValidacao($this->renderizarFormularioHtml('Senha inválida.'));
        }

        return Response::redirecionar('/');
    }

    public function sair(Request $request): Response
    {
        PortaoDeAnalista::sair();

        return Response::redirecionar(self::ROTA);
    }

    private function renderizarFormulario(?string $erro = null): Response
    {
        return Response::ok($this->renderizarFormularioHtml($erro));
    }

    private function renderizarFormularioHtml(?string $erro = null): string
    {
        return $this->viewRenderer->renderComLayout(
            'autenticacao/entrar',
            ['erro' => $erro],
            'Entrar',
            self::ROTA
        );
    }
}
