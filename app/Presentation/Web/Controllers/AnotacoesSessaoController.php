<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;

/**
 * Endpoint de autosave das anotações do analista sobre uma Sessao — não
 * estende AbstractCrudResourceController (mesmo padrão não-CRUD de
 * ConversaController): só um proxy JSON fino para
 * PUT /sessions/{id}/annotation, chamado via fetch() a cada debounce pela
 * view de Sessão. Nunca renderiza HTML.
 */
final class AnotacoesSessaoController
{
    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function salvar(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $conteudo = (string) $request->input('conteudo', '');

        $resposta = $this->httpClient->put('sessions/' . $id . '/annotation', ['conteudo' => $conteudo]);

        if (!$resposta->sucesso) {
            return Response::json(['sucesso' => false], 502);
        }

        return Response::json([
            'sucesso' => true,
            'atualizadoEm' => $resposta->dados['atualizadoEm'] ?? null,
        ]);
    }
}
