<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\GravacaoAudioDTO;

final class GravacaoAudioResponse
{
    private function __construct(
        private readonly GravacaoAudioDTO $dto
    ) {
    }

    public static function fromDTO(GravacaoAudioDTO $dto): self
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
            'status' => $this->dto->status,
            'criadaEm' => $this->dto->criadaEm,
            'transcritaEm' => $this->dto->transcritaEm,
        ];
    }
}
