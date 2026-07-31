<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\AnalistaApplicationService;
use PsycheAI\Presentation\Http\HttpException;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Requests\AutenticarAnalistaRequest;
use PsycheAI\Presentation\Responses\AnalistaResponse;

/**
 * Sprint 18 (Plataforma): endpoint consumido pela Web
 * (`Presentation/Web/Controllers/AutenticacaoAnalistaController`) para
 * verificar credenciais de analista — a Web nunca fala com o banco
 * diretamente, só com esta API (mesmo padrão de todo o resto do sistema).
 */
final class AutenticacaoController extends Controller
{
    public function __construct(
        private readonly AnalistaApplicationService $service
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function autenticar(Request $request, array $params): JsonResponse
    {
        $dados = AutenticarAnalistaRequest::fromArray($request->corpo());

        $dto = $this->service->autenticar($dados->email, $dados->senha);

        if ($dto === null) {
            throw HttpException::naoAutorizado('E-mail ou senha inválidos.');
        }

        return $this->sucesso(AnalistaResponse::fromDTO($dto)->toArray());
    }
}
