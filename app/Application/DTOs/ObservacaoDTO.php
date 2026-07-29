<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\Observacao;

final class ObservacaoDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $texto
    ) {
    }

    public static function fromEntity(Observacao $observacao): self
    {
        return new self(
            id: $observacao->id()->valor(),
            texto: $observacao->texto()->valor()
        );
    }
}
