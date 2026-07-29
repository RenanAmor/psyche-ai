<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\DiscursoApplicationService;
use PsycheAI\Presentation\Http\HttpException;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Requests\AtualizarDiscursoRequest;
use PsycheAI\Presentation\Requests\CriarDiscursoRequest;
use PsycheAI\Presentation\Responses\DiscursoResponse;

final class DiscursoController extends Controller
{
    public function __construct(
        private readonly DiscursoApplicationService $service
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function criar(Request $request, array $params): JsonResponse
    {
        $dados = CriarDiscursoRequest::fromArray($request->corpo());

        if ($this->service->buscarPorId($dados->id) !== null) {
            throw HttpException::conflito(sprintf('Discurso com id "%s" já existe.', $dados->id));
        }

        $dto = $this->service->criar($dados->sessaoId, $dados->id, $dados->conteudo);

        return $this->criado(DiscursoResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function listar(Request $request, array $params): JsonResponse
    {
        $itens = array_map(
            static fn ($dto): array => DiscursoResponse::fromDTO($dto)->toArray(),
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
            throw HttpException::naoEncontrado(sprintf('Discurso com id "%s" não foi encontrado.', $params['id']));
        }

        return $this->sucesso(DiscursoResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function atualizar(Request $request, array $params): JsonResponse
    {
        $dados = AtualizarDiscursoRequest::fromArray($request->corpo());

        $dto = $this->service->atualizar($params['id'], $dados->conteudo);

        return $this->sucesso(DiscursoResponse::fromDTO($dto)->toArray());
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
