<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\Discurso;

final class DiscursoDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $conteudo,
        public readonly int $quantidadeDeEventos
    ) {
    }

    public static function fromEntity(Discurso $discurso): self
    {
        return new self(
            id: $discurso->id()->valor(),
            conteudo: $discurso->conteudo()->valor(),
            quantidadeDeEventos: count($discurso->eventos())
        );
    }
}
