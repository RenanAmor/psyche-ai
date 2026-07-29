<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Client;

use PsycheAI\Presentation\Web\Errors\ErrorViewModel;

/**
 * Envelope de resposta do Cliente HTTP, simétrico ao que a futura API
 * REST devolverá: ou os dados foram obtidos com sucesso, ou um
 * ErrorViewModel explica o que falhou. Controllers nunca lidam com
 * status HTTP cru — apenas com este envelope.
 */
final class ApiResponse
{
    /**
     * @param array<int, array<string, mixed>>|array<string, mixed> $dados
     */
    private function __construct(
        public readonly bool $sucesso,
        public readonly array $dados = [],
        public readonly ?ErrorViewModel $erro = null
    ) {
    }

    /**
     * @param array<int, array<string, mixed>>|array<string, mixed> $dados
     */
    public static function sucesso(array $dados): self
    {
        return new self(true, $dados);
    }

    public static function falha(ErrorViewModel $erro): self
    {
        return new self(false, [], $erro);
    }
}
