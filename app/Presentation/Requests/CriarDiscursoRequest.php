<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

final class CriarDiscursoRequest extends HttpRequestData
{
    public function __construct(
        public readonly string $sessaoId,
        public readonly string $id,
        public readonly string $conteudo
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            sessaoId: self::exigirString($dados, 'sessaoId'),
            id: self::exigirString($dados, 'id'),
            conteudo: self::exigirString($dados, 'conteudo')
        );
    }
}
