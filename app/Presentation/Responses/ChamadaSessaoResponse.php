<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\ChamadaSessaoDTO;

final class ChamadaSessaoResponse
{
    private function __construct(
        private readonly ChamadaSessaoDTO $dto
    ) {
    }

    public static function fromDTO(ChamadaSessaoDTO $dto): self
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
            'sessaoId' => $this->dto->sessaoId,
            'salaUrl' => $this->dto->salaUrl,
            'status' => $this->dto->status,
            'criadaEm' => $this->dto->criadaEm,
            'expiraEm' => $this->dto->expiraEm,
            'encerradaEm' => $this->dto->encerradaEm,
        ];
    }
}
