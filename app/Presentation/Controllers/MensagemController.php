<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\MensagemApplicationService;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Requests\EnviarMensagemRequest;
use PsycheAI\Presentation\Responses\MensagemEnviadaResponse;

/**
 * Expõe o caso de uso "enviar mensagem" da Sprint 12: registra a
 * mensagem do usuário e a resposta automática do sistema em uma única
 * chamada, sobre MensagemApplicationService. RecursoNaoEncontradoException
 * (Sessao inexistente) e ComandoInvalidoException (conteúdo inválido) são
 * traduzidas para 404/422 pelo ExceptionHandler global, como em todos os
 * outros Controllers.
 */
final class MensagemController extends Controller
{
    public function __construct(
        private readonly MensagemApplicationService $service
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function enviar(Request $request, array $params): JsonResponse
    {
        $dados = EnviarMensagemRequest::fromArray($request->corpo());

        $dto = $this->service->enviar($params['id'], $dados->conteudo);

        return $this->criado(MensagemEnviadaResponse::fromDTO($dto)->toArray());
    }
}
