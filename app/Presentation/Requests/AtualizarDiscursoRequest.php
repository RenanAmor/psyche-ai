<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

final class AtualizarDiscursoRequest extends HttpRequestData
{
    public function __construct(
        public readonly string $conteudo
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(conteudo: self::exigirString($dados, 'conteudo'));
    }
}
