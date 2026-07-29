<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\DiscursoDTO;

final class DiscursoResponse
{
    private function __construct(
        private readonly DiscursoDTO $dto
    ) {
    }

    public static function fromDTO(DiscursoDTO $dto): self
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
            'conteudo' => $this->dto->conteudo,
            'quantidadeDeEventos' => $this->dto->quantidadeDeEventos,
        ];
    }
}
