<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\ListaDeEsperaApplicationService;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Requests\InscreverListaDeEsperaRequest;
use PsycheAI\Presentation\Responses\InscricaoListaDeEsperaResponse;

/**
 * Endpoint consumido pela Web (`Presentation/Web/Controllers/
 * ListaDeEsperaController`, formulário público na tela de login da ECO) e
 * pela tela de inscritos do Analista (`InscritosListaDeEsperaController`)
 * — sem qualquer relação com AutenticacaoController/
 * AutenticacaoParticipanteController: não há credencial nem sessão aqui.
 */
final class ListaDeEsperaController extends Controller
{
    public function __construct(
        private readonly ListaDeEsperaApplicationService $service
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function inscrever(Request $request, array $params): JsonResponse
    {
        $dados = InscreverListaDeEsperaRequest::fromArray($request->corpo());

        $dto = $this->service->inscrever(
            $dados->email,
            $dados->nome,
            $dados->profissao,
            $dados->instituicao,
            $dados->paisEstado,
            $dados->motivoInteresse,
            $dados->aceitouPoliticaPrivacidade,
            $dados->aceitouTermoConsentimento,
            $dados->origem
        );

        return $this->criado(InscricaoListaDeEsperaResponse::fromDTO($dto)->toArray());
    }

    /**
     * @param array<string, string> $params
     */
    public function listar(Request $request, array $params): JsonResponse
    {
        $itens = array_map(
            static fn ($dto): array => InscricaoListaDeEsperaResponse::fromDTO($dto)->toArray(),
            $this->service->listar()
        );

        return $this->sucesso($itens);
    }
}
