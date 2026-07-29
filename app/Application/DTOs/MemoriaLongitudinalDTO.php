<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\MemoriaLongitudinal;

final class MemoriaLongitudinalDTO
{
    /**
     * @param SessaoDTO[] $sessoes
     */
    public function __construct(
        public readonly string $id,
        public readonly int $quantidadeDeSessoes,
        public readonly array $sessoes
    ) {
    }

    public static function fromEntity(MemoriaLongitudinal $memoria): self
    {
        return new self(
            id: $memoria->id()->valor(),
            quantidadeDeSessoes: $memoria->quantidadeDeSessoes(),
            sessoes: array_map(
                static fn ($sessao) => SessaoDTO::fromEntity($sessao),
                $memoria->sessoes()
            )
        );
    }
}
