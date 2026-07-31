<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;
use PsycheAI\Presentation\Web\Security\PortaoDeAnalista;

/**
 * Tela de entrada/saída do Portão do Analista (revisão pós-Sprint 16).
 * Não protegida por `PortaoDeAnalista::proteger()` — é justamente a
 * porta de acesso a ele.
 *
 * Desde a Sprint 18 (Plataforma), a verificação de credencial não
 * acontece mais aqui: este Controller chama `POST /auth/login` na API
 * REST (mesmo `HttpClientInterface` injetado em todos os outros
 * Controllers Web) e só abre a sessão do Portão se a API confirmar a
 * conta real do analista.
 */
final class AutenticacaoAnalistaController
{
    private const ROTA = '/entrar';
    private const MENSAGEM_CREDENCIAIS_INVALIDAS = 'E-mail ou senha inválidos.';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ViewRenderer $viewRenderer = new ViewRenderer()
    ) {
    }

    public function entrar(Request $request): Response
    {
        return $this->renderizarFormulario();
    }

    public function autenticar(Request $request): Response
    {
        $email = (string) $request->input('email', '');
        $senha = (string) $request->input('senha', '');

        $resposta = $this->httpClient->post('auth/login', ['email' => $email, 'senha' => $senha]);

        if (!$resposta->sucesso) {
            return Response::erroValidacao($this->renderizarFormularioHtml(self::MENSAGEM_CREDENCIAIS_INVALIDAS));
        }

        /** @var string $analistaId */
        $analistaId = $resposta->dados['id'];

        PortaoDeAnalista::abrirSessao($analistaId);

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
