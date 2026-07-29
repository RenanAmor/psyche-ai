<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\EventoDiscursivo;

final class EventoDiscursivoDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $conteudo,
        public readonly int $posicao
    ) {
    }

    public static function fromEntity(EventoDiscursivo $evento): self
    {
        return new self(
            id: $evento->id()->valor(),
            conteudo: $evento->conteudo()->valor(),
            posicao: $evento->posicao()->valor()
        );
    }
}
