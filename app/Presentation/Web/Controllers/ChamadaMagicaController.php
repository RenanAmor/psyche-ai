<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;

/**
 * Acesso do Sujeito à videochamada por link mágico — rota pública,
 * deliberadamente fora de PortaoDeAnalista e PortaoDeParticipante (o
 * próprio token, validado pela API, faz o papel de autenticação). Nunca
 * exige conta/login: é o mecanismo de acesso que o analista envia por
 * fora (WhatsApp/e-mail) para uma consulta marcada.
 */
final class ChamadaMagicaController
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ViewRenderer $viewRenderer = new ViewRenderer()
    ) {
    }

    public function entrar(Request $request): Response
    {
        $token = (string) $request->routeParam('token', '');
        $resposta = $this->httpClient->post('videocalls/' . $token . '/join');

        if (!$resposta->sucesso) {
            $html = $this->viewRenderer->renderComLayout(
                'chamada/link-invalido',
                [],
                'Link inválido',
                '/videochamada/entrar/' . $token,
                'layout-eco'
            );

            return new Response($html, 404);
        }

        $html = $this->viewRenderer->renderComLayout(
            'chamada/entrar',
            [
                'salaUrl' => (string) $resposta->dados['salaUrl'],
                'tokenSujeito' => (string) $resposta->dados['tokenSujeito'],
            ],
            'Videochamada',
            '/videochamada/entrar/' . $token,
            'layout-eco'
        );

        return Response::ok($html);
    }
}
