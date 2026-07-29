<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\Recorrencia;

final class RecorrenciaDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $descricao,
        public readonly int $frequencia
    ) {
    }

    public static function fromEntity(Recorrencia $recorrencia): self
    {
        return new self(
            id: $recorrencia->id()->valor(),
            descricao: $recorrencia->descricao()->valor(),
            frequencia: $recorrencia->frequencia()->valor()
        );
    }
}
