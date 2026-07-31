<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\Sujeito;

final class SujeitoDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $nome,
        public readonly int $quantidadeDeSessoes,
        public readonly ?string $email = null
    ) {
    }

    public static function fromEntity(Sujeito $sujeito): self
    {
        return new self(
            id: $sujeito->id()->valor(),
            nome: $sujeito->nome()->valor(),
            quantidadeDeSessoes: count($sujeito->sessoes()),
            email: $sujeito->email()?->valor()
        );
    }
}
