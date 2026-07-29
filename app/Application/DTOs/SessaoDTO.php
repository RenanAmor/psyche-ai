<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\Sessao;

final class SessaoDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $data,
        public readonly int $quantidadeDeDiscursos
    ) {
    }

    public static function fromEntity(Sessao $sessao): self
    {
        return new self(
            id: $sessao->id()->valor(),
            data: (string) $sessao->data(),
            quantidadeDeDiscursos: count($sessao->discursos())
        );
    }
}
