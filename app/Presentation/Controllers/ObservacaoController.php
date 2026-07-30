<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\ObservacaoApplicationService;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Requests\ConsultarObservacoesRequest;
use PsycheAI\Presentation\Responses\ObservacaoResponse;

final class ObservacaoController extends Controller
{
    public function __construct(
        private readonly ObservacaoApplicationService $observacoes
    ) {
    }

    /** @param array<string, string> $params */
    public function observar(Request $request, array $params): JsonResponse
    {
        $filtro = ConsultarObservacoesRequest::fromArray($request->queries());

        $resultado = $this->observacoes->consultar(
            $params['id'],
            $filtro->minimoDeRecorrencia,
            $filtro->comLeituraLacaniana
        );

        return $this->sucesso(ObservacaoResponse::fromDTO($resultado)->toArray());
    }
}
