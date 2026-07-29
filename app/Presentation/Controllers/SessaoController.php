<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\SessaoApplicationService;
use PsycheAI\Presentation\Http\HttpException;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Requests\AtualizarSessaoRequest;
use PsycheAI\Presentation\Requests\CriarSessaoRequest;
use PsycheAI\Presentation\Responses\SessaoResponse;

final class SessaoController extends Controller
{
    public function __construct(
        private readonly SessaoApplicationService $service
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function criar(Request $request, array $params): JsonResponse
    {
        $dados = CriarSessaoRequest::fromArray($request->corpo());

        if ($this->service->buscarPorId($dados->id) !== null) {
            throw HttpException::conflito(sprintf('Sessao com id "%s" já existe.', $dados->id));
        }

        $dto = $this->service->criar($dados->sujeitoId, $dados->id, $dados->data);

        return $this->criado(SessaoResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function listar(Request $request, array $params): JsonResponse
    {
        $itens = array_map(
            static fn ($dto): array => SessaoResponse::fromDTO($dto)->toArray(),
            $this->service->listar()
        );

        return $this->sucesso($itens);
    }

    /**
     * @param array<string, string> $params
     */
    public function buscar(Request $request, array $params): JsonResponse
    {
        $dto = $this->service->buscarPorId($params['id']);

        if ($dto === null) {
            throw HttpException::naoEncontrado(sprintf('Sessao com id "%s" não foi encontrada.', $params['id']));
        }

        return $this->sucesso(SessaoResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function atualizar(Request $request, array $params): JsonResponse
    {
        $dados = AtualizarSessaoRequest::fromArray($request->corpo());

        $dto = $this->service->atualizar($params['id'], $dados->data);

        return $this->sucesso(SessaoResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function excluir(Request $request, array $params): JsonResponse
    {
        $this->service->excluir($params['id']);

        return $this->semConteudo();
    }
}
