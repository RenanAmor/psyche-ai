<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Http;

use InvalidArgumentException;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Domain\Exceptions\DomainException;
use Throwable;

/**
 * Converte qualquer exceção não tratada pelos Controllers em uma
 * JsonResponse padronizada, traduzindo exceções de Application/Domain
 * para os códigos HTTP definidos pela Sprint 10 sem que essas camadas
 * precisem conhecer HTTP.
 */
final class ExceptionHandler
{
    public static function converter(Throwable $erro): JsonResponse
    {
        return match (true) {
            $erro instanceof HttpException => JsonResponse::erro($erro->getMessage(), $erro->statusCode(), $erro->erros()),
            $erro instanceof RecursoNaoEncontradoException => JsonResponse::erro($erro->getMessage(), 404),
            $erro instanceof ComandoInvalidoException => JsonResponse::erro($erro->getMessage(), 422),
            $erro instanceof InvalidArgumentException => JsonResponse::erro($erro->getMessage(), 422),
            $erro instanceof DomainException => JsonResponse::erro($erro->getMessage(), 422),
            default => JsonResponse::erro('Erro interno do servidor.', 500),
        };
    }
}
