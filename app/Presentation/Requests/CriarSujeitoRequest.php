<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

final class CriarSujeitoRequest extends HttpRequestData
{
    public function __construct(
        public readonly string $id,
        public readonly string $nome
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            id: self::exigirString($dados, 'id'),
            nome: self::exigirString($dados, 'nome')
        );
    }
}
