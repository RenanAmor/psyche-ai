<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\SujeitoApplicationService;
use PsycheAI\Presentation\Http\HttpException;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Requests\AutenticarSujeitoRequest;
use PsycheAI\Presentation\Responses\SujeitoResponse;

/**
 * Sprint 20 ("Contas reais do Sujeito"): login por e-mail/senha, para o
 * Sujeito recuperar seu espaço singular a partir de qualquer navegador
 * — diferente do cadastro (`SujeitoController::registrarConta()`, que
 * já sabe o id via cookie), o login não sabe o id de antemão, por isso é
 * um endpoint próprio em vez de sub-recurso de `/subjects/{id}`.
 */
final class AutenticacaoSujeitoController extends Controller
{
    public function __construct(
        private readonly SujeitoApplicationService $service
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function autenticar(Request $request, array $params): JsonResponse
    {
        $dados = AutenticarSujeitoRequest::fromArray($request->corpo());

        $dto = $this->service->autenticar($dados->email, $dados->senha);

        if ($dto === null) {
            throw HttpException::naoAutorizado('E-mail ou senha inválidos.');
        }

        return $this->sucesso(SujeitoResponse::fromDTO($dto)->toArray());
    }
}
