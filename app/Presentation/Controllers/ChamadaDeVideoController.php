<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\ChamadaDeVideoApplicationService;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Responses\ChamadaSessaoResponse;

final class ChamadaDeVideoController extends Controller
{
    public function __construct(
        private readonly ChamadaDeVideoApplicationService $service
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function iniciar(Request $request, array $params): JsonResponse
    {
        $resultado = $this->service->iniciar($params['id']);

        return $this->sucesso([
            'chamada' => ChamadaSessaoResponse::fromDTO($resultado['chamada'])->toArray(),
            'tokenAnalista' => $resultado['tokenAnalista'],
            'tokenAcesso' => $resultado['tokenAcesso'],
        ]);
    }

    /**
     * @param array<string, string> $params
     */
    public function entrar(Request $request, array $params): JsonResponse
    {
        $resultado = $this->service->entrarComToken($params['token']);

        return $this->sucesso($resultado);
    }

    /**
     * @param array<string, string> $params
     */
    public function encerrar(Request $request, array $params): JsonResponse
    {
        $this->service->encerrar($params['id']);

        return $this->semConteudo();
    }
}
