<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;
use PsycheAI\Presentation\Web\ViewModels\DashboardViewModel;

final class DashboardController
{
    private const ROTA = '/';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ViewRenderer $viewRenderer = new ViewRenderer()
    ) {
    }

    public function index(Request $request): Response
    {
        if ($request->query('estado') === 'carregando') {
            return new Response(
                $this->viewRenderer->renderComLayout(
                    'carregando',
                    ['rotulo' => 'Carregando Dashboard...'],
                    'Dashboard',
                    self::ROTA
                )
            );
        }

        $sujeitos = $this->httpClient->get('subjects');
        $sessoes = $this->httpClient->get('sessions');
        $discursos = $this->httpClient->get('discourses');
        $memorias = $this->httpClient->get('memories');
        $eventos = $this->httpClient->get('events');

        foreach ([$sujeitos, $sessoes, $discursos, $memorias, $eventos] as $resposta) {
            if (!$resposta->sucesso) {
                /** @var \PsycheAI\Presentation\Web\Errors\ErrorViewModel $erro */
                $erro = $resposta->erro;

                return ErrorController::renderizar($erro, $this->viewRenderer, self::ROTA);
            }
        }

        $dashboard = DashboardViewModel::fromListas(
            $sujeitos->dados,
            $sessoes->dados,
            $discursos->dados,
            $memorias->dados,
            $eventos->dados
        );

        $html = $this->viewRenderer->renderComLayout('dashboard', ['dashboard' => $dashboard], 'Dashboard', self::ROTA);

        return Response::ok($html);
    }
}
