<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\DiscursoApplicationService;
use PsycheAI\Presentation\Http\HttpException;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Http\Response;
use PsycheAI\Presentation\Requests\AtualizarEventoDiscursivoRequest;
use PsycheAI\Presentation\Requests\CriarEventoDiscursivoRequest;
use PsycheAI\Presentation\Responses\EventoDiscursivoResponse;

/**
 * EventoDiscursivo não é raiz de agregado (decisão da Sprint 9) — não
 * possui Application Service própria. Este Controller expõe /events sobre
 * as operações de EventoDiscursivo já publicadas por
 * DiscursoApplicationService (adicionarEvento/listarEventos/buscarEvento/
 * atualizarEvento/removerEvento), preservando o limite do agregado
 * Discurso em vez de criar um repositório dedicado só para a API.
 */
final class EventoDiscursivoController extends Controller
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
        $dados = CriarEventoDiscursivoRequest::fromArray($request->corpo());

        if ($this->service->buscarEvento($dados->id) !== null) {
            throw HttpException::conflito(sprintf('EventoDiscursivo com id "%s" já existe.', $dados->id));
        }

        $this->service->adicionarEvento($dados->discursoId, $dados->id, $dados->conteudo, $dados->posicao);

        $evento = $this->service->buscarEvento($dados->id);

        return $this->criado(EventoDiscursivoResponse::fromDTO($evento)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function listar(Request $request, array $params): JsonResponse
    {
        $itens = array_map(
            static fn ($dto): array => EventoDiscursivoResponse::fromDTO($dto)->toArray(),
            $this->service->listarEventos()
        );

        return $this->sucesso($itens);
    }

    /**
     * @param array<string, string> $params
     */
    public function buscar(Request $request, array $params): JsonResponse
    {
        $dto = $this->service->buscarEvento($params['id']);

        if ($dto === null) {
            throw HttpException::naoEncontrado(sprintf('EventoDiscursivo com id "%s" não foi encontrado.', $params['id']));
        }

        return $this->sucesso(EventoDiscursivoResponse::fromDTO($dto)->toArray());
    }

    /**
     * Sintetiza em voz o conteúdo do EventoDiscursivo (Sprint 24, Voz de
     * Saída) e devolve os bytes de áudio brutos — nunca o envelope JSON
     * padrão, mesma exceção já aberta por GravacaoAudioController::baixar.
     *
     * @param array<string, string> $params
     */
    public function falar(Request $request, array $params): Response
    {
        $audio = $this->service->sintetizarFalaDoEvento($params['id']);

        return new Response(200, $audio, ['Content-Type' => 'audio/mpeg']);
    }

    /**
     * @param array<string, string> $params
     */
    public function atualizar(Request $request, array $params): JsonResponse
    {
        $dados = AtualizarEventoDiscursivoRequest::fromArray($request->corpo());

        $dto = $this->service->atualizarEvento($params['id'], $dados->conteudo, $dados->posicao);

        return $this->sucesso(EventoDiscursivoResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function excluir(Request $request, array $params): JsonResponse
    {
        $this->service->removerEvento($params['id']);

        return $this->semConteudo();
    }
}
