<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\AnotacaoSessaoApplicationService;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Requests\SalvarAnotacaoSessaoRequest;
use PsycheAI\Presentation\Responses\AnotacaoSessaoResponse;

final class AnotacaoSessaoController extends Controller
{
    public function __construct(
        private readonly AnotacaoSessaoApplicationService $service
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function salvar(Request $request, array $params): JsonResponse
    {
        $dados = SalvarAnotacaoSessaoRequest::fromArray($request->corpo());

        $dto = $this->service->salvar($params['id'], $dados->conteudo);

        return $this->sucesso(AnotacaoSessaoResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function buscar(Request $request, array $params): JsonResponse
    {
        $dto = $this->service->buscarPorSessao($params['id']);

        return $this->sucesso(AnotacaoSessaoResponse::fromDTO($dto)->toArray());
    }
}
