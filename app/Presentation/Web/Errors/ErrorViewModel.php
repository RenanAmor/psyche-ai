<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Errors;

/**
 * Representa um erro pronto para exibição na interface — nunca uma
 * exceção de Domain/Application, que a Presentation não conhece.
 */
final class ErrorViewModel
{
    /**
     * @param string[] $detalhes
     */
    public function __construct(
        public readonly ErrorType $tipo,
        public readonly string $titulo,
        public readonly string $mensagem,
        public readonly array $detalhes = []
    ) {
    }

    public function statusHttp(): int
    {
        return match ($this->tipo) {
            ErrorType::COMUNICACAO => 502,
            ErrorType::NAO_ENCONTRADO => 404,
            ErrorType::VALIDACAO => 422,
            ErrorType::INTERNO => 500,
            ErrorType::TIMEOUT => 504,
        };
    }
}
