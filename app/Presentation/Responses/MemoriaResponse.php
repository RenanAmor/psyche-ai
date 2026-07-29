<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\MemoriaLongitudinalDTO;
use PsycheAI\Application\DTOs\SessaoDTO;

final class MemoriaResponse
{
    private function __construct(
        private readonly MemoriaLongitudinalDTO $dto
    ) {
    }

    public static function fromDTO(MemoriaLongitudinalDTO $dto): self
    {
        return new self($dto);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->dto->id,
            'quantidadeDeSessoes' => $this->dto->quantidadeDeSessoes,
            'sessoes' => array_map(
                static fn (SessaoDTO $sessao): array => SessaoResponse::fromDTO($sessao)->toArray(),
                $this->dto->sessoes
            ),
        ];
    }
}
