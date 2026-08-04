<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Http\BasePath;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;

/**
 * Tela do analista para iniciar/encerrar a videochamada embutida (Daily.co)
 * de uma Sessao. Não estende AbstractCrudResourceController: fluxo próprio
 * de ação, não CRUD de listagem/formulário.
 */
final class ChamadaDeVideoController
{
    private const ROTA_SESSOES = '/sessoes';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ViewRenderer $viewRenderer = new ViewRenderer()
    ) {
    }

    public function iniciar(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $resposta = $this->httpClient->post('sessions/' . $id . '/videocall');

        if (!$resposta->sucesso) {
            /** @var \PsycheAI\Presentation\Web\Errors\ErrorViewModel $erro */
            $erro = $resposta->erro;

            return ErrorController::renderizar($erro, $this->viewRenderer, self::ROTA_SESSOES);
        }

        $linkMagico = BasePath::url('/videochamada/entrar/' . (string) $resposta->dados['tokenAcesso']);

        $html = $this->viewRenderer->renderComLayout(
            'sessoes/videochamada',
            [
                'sessaoId' => $id,
                'salaUrl' => (string) $resposta->dados['chamada']['salaUrl'],
                'tokenAnalista' => (string) $resposta->dados['tokenAnalista'],
                'linkMagico' => $linkMagico,
            ],
            'Videochamada',
            self::ROTA_SESSOES
        );

        return Response::ok($html);
    }

    public function encerrar(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $this->httpClient->post('sessions/' . $id . '/videocall/encerrar');

        return Response::redirecionar('/sessoes/' . $id);
    }
}
