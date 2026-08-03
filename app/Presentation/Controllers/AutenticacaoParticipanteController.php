<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\ParticipanteApplicationService;
use PsycheAI\Presentation\Http\HttpException;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Requests\AutenticarParticipanteRequest;
use PsycheAI\Presentation\Responses\ParticipanteResponse;

/**
 * Endpoint consumido pela Web
 * (`Presentation/Web/Controllers/AutenticacaoParticipanteController`) para
 * verificar credenciais de participante da ECO — a Web nunca fala com o
 * banco diretamente, só com esta API (mesmo padrão de AutenticacaoController,
 * o equivalente do Analista). Sistema de autenticação independente: não
 * reaproveita `AnalistaApplicationService` nem a extinta
 * `AutenticacaoSujeitoController` (Sprint 20, self-signup).
 */
final class AutenticacaoParticipanteController extends Controller
{
    public function __construct(
        private readonly ParticipanteApplicationService $service
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function autenticar(Request $request, array $params): JsonResponse
    {
        $dados = AutenticarParticipanteRequest::fromArray($request->corpo());

        $dto = $this->service->autenticar($dados->email, $dados->senha);

        if ($dto === null) {
            throw HttpException::naoAutorizado('E-mail ou senha inválidos.');
        }

        return $this->sucesso(ParticipanteResponse::fromDTO($dto)->toArray());
    }
}
