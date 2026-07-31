<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

final class AutenticarSujeitoRequest extends HttpRequestData
{
    public function __construct(
        public readonly string $email,
        public readonly string $senha
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            email: self::exigirString($dados, 'email'),
            senha: self::exigirString($dados, 'senha')
        );
    }
}
