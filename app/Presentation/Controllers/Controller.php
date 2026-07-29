<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Presentation\Http\JsonResponse;

/**
 * Base para os Controllers da API: apenas atalhos para o envelope JSON
 * padronizado. Controllers concretos recebem, no construtor, apenas as
 * Application Services de que precisam — nunca Infraestrutura concreta,
 * e nenhuma regra de negócio é decidida aqui.
 */
abstract class Controller
{
    protected function sucesso(mixed $data, int $status = 200): JsonResponse
    {
        return JsonResponse::sucesso($data, $status);
    }

    protected function criado(mixed $data): JsonResponse
    {
        return JsonResponse::sucesso($data, 201);
    }

    protected function semConteudo(): JsonResponse
    {
        return JsonResponse::semConteudo();
    }
}
