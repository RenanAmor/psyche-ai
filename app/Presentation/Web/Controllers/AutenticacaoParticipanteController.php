<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;
use PsycheAI\Presentation\Web\Security\PortaoDeParticipante;

/**
 * Tela de entrada/saída do Portão da ECO — não protegida por
 * `PortaoDeParticipante::proteger()`, é justamente a porta de acesso a ele.
 * Mesmo padrão de `AutenticacaoAnalistaController`: a verificação de
 * credencial não acontece aqui, este Controller chama
 * `POST /auth/participante/login` na API REST e só abre a sessão do Portão
 * se a API confirmar a conta real do participante. Ao contrário do Portão
 * do Analista, o sucesso redireciona para `/conversa` (a ECO), não para o
 * Dashboard.
 */
final class AutenticacaoParticipanteController
{
    private const ROTA = '/participante/entrar';
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

        $resposta = $this->httpClient->post('auth/participante/login', ['email' => $email, 'senha' => $senha]);

        if (!$resposta->sucesso) {
            return Response::erroValidacao($this->renderizarFormularioHtml(self::MENSAGEM_CREDENCIAIS_INVALIDAS));
        }

        /** @var string $participanteId */
        $participanteId = $resposta->dados['id'];

        PortaoDeParticipante::abrirSessao($participanteId);

        return Response::redirecionar('/conversa');
    }

    public function sair(Request $request): Response
    {
        PortaoDeParticipante::sair();

        return Response::redirecionar(self::ROTA);
    }

    private function renderizarFormulario(?string $erro = null): Response
    {
        return Response::ok($this->renderizarFormularioHtml($erro));
    }

    private function renderizarFormularioHtml(?string $erro = null): string
    {
        return $this->viewRenderer->renderComLayout(
            'autenticacao/entrar-participante',
            ['erro' => $erro],
            'Entrar',
            self::ROTA,
            'layout-eco'
        );
    }
}
