<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\ConsolidacaoApplicationService;
use PsycheAI\Application\Services\LinhaDoTempoApplicationService;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Requests\ConsultarLinhaDoTempoRequest;
use PsycheAI\Presentation\Responses\ConsolidacaoResponse;
use PsycheAI\Presentation\Responses\LinhaDoTempoResponse;

/**
 * Únicos dois endpoints de consulta longitudinal exigidos pela Sprint
 * 13: a Linha do Tempo Discursiva de um Sujeito (paginada e filtrável) e
 * a Consolidação (contagens) da sua Memória. Ambos somente-leitura,
 * compostos sobre as Application Services já existentes — nenhum
 * endpoint de escrita novo.
 */
final class LinhaDoTempoController extends Controller
{
    public function __construct(
        private readonly LinhaDoTempoApplicationService $linhaDoTempo,
        private readonly ConsolidacaoApplicationService $consolidacao
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function timeline(Request $request, array $params): JsonResponse
    {
        $filtro = ConsultarLinhaDoTempoRequest::fromArray($request->queries());

        $resultado = $this->linhaDoTempo->consultar(
            $params['id'],
            $filtro->tipo,
            $filtro->de,
            $filtro->ate,
            $filtro->q,
            $filtro->pagina,
            $filtro->porPagina
        );

        return $this->sucesso(LinhaDoTempoResponse::fromDTO($resultado)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function consolidacao(Request $request, array $params): JsonResponse
    {
        $dto = $this->consolidacao->consolidar($params['id']);

        return $this->sucesso(ConsolidacaoResponse::fromDTO($dto)->toArray());
    }
}
