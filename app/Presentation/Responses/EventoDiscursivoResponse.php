<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\EventoDiscursivoDTO;

final class EventoDiscursivoResponse
{
    private function __construct(
        private readonly EventoDiscursivoDTO $dto
    ) {
    }

    public static function fromDTO(EventoDiscursivoDTO $dto): self
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
            'discursoId' => $this->dto->discursoId,
            'conteudo' => $this->dto->conteudo,
            'posicao' => $this->dto->posicao,
        ];
    }
}
