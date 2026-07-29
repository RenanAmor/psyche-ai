<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Presentation\Http\HttpException;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Requests\AtualizarSujeitoRequest;
use PsycheAI\Presentation\Requests\CriarSujeitoRequest;
use PsycheAI\Presentation\Responses\SujeitoResponse;

final class SujeitoController extends Controller
{
    public function __construct(
        private readonly SujeitoApplicationService $service
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function criar(Request $request, array $params): JsonResponse
    {
        $dados = CriarSujeitoRequest::fromArray($request->corpo());

        if ($this->service->buscarPorId($dados->id) !== null) {
            throw HttpException::conflito(sprintf('Sujeito com id "%s" já existe.', $dados->id));
        }

        $dto = $this->service->criar($dados->id, $dados->nome);

        return $this->criado(SujeitoResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function listar(Request $request, array $params): JsonResponse
    {
        $itens = array_map(
            static fn ($dto): array => SujeitoResponse::fromDTO($dto)->toArray(),
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
            throw HttpException::naoEncontrado(sprintf('Sujeito com id "%s" não foi encontrado.', $params['id']));
        }

        return $this->sucesso(SujeitoResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function atualizar(Request $request, array $params): JsonResponse
    {
        $dados = AtualizarSujeitoRequest::fromArray($request->corpo());

        $dto = $this->service->atualizar($params['id'], $dados->nome);

        return $this->sucesso(SujeitoResponse::fromDTO($dto)->toArray());
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
