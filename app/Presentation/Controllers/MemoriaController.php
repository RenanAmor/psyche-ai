<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\MemoriaApplicationService;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\Repositories\SujeitoRepository;
use PsycheAI\Presentation\Http\HttpException;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Requests\AtualizarMemoriaRequest;
use PsycheAI\Presentation\Requests\CriarMemoriaRequest;
use PsycheAI\Presentation\Responses\MemoriaResponse;

/**
 * MemoriaApplicationService::criar()/atualizar() recebem a Entidade
 * Sujeito já hidratada (não um id) — ver o comentário equivalente em
 * ApplicationServiceProviderTest. Por isso este Controller depende do
 * contrato de Domínio SujeitoRepository além da Application Service, e
 * não apenas desta última.
 */
final class MemoriaController extends Controller
{
    public function __construct(
        private readonly MemoriaApplicationService $service,
        private readonly SujeitoRepository $sujeitoRepository
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function criar(Request $request, array $params): JsonResponse
    {
        $dados = CriarMemoriaRequest::fromArray($request->corpo());

        if ($this->service->buscarPorId($dados->id) !== null) {
            throw HttpException::conflito(sprintf('MemoriaLongitudinal com id "%s" já existe.', $dados->id));
        }

        $sujeito = $this->buscarSujeito($dados->sujeitoId);

        $dto = $this->service->criar($sujeito, $dados->id);

        return $this->criado(MemoriaResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function listar(Request $request, array $params): JsonResponse
    {
        $itens = array_map(
            static fn ($dto): array => MemoriaResponse::fromDTO($dto)->toArray(),
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
            throw HttpException::naoEncontrado(sprintf('MemoriaLongitudinal com id "%s" não foi encontrada.', $params['id']));
        }

        return $this->sucesso(MemoriaResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function atualizar(Request $request, array $params): JsonResponse
    {
        $dados = AtualizarMemoriaRequest::fromArray($request->corpo());

        $sujeito = $this->buscarSujeito($dados->sujeitoId);

        $dto = $this->service->atualizar($sujeito, $params['id']);

        return $this->sucesso(MemoriaResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function excluir(Request $request, array $params): JsonResponse
    {
        $this->service->excluir($params['id']);

        return $this->semConteudo();
    }

    private function buscarSujeito(string $sujeitoId): Sujeito
    {
        $sujeito = $this->sujeitoRepository->findById($sujeitoId);

        if ($sujeito === null) {
            throw HttpException::naoEncontrado(sprintf('Sujeito com id "%s" não foi encontrado.', $sujeitoId));
        }

        return $sujeito;
    }
}
