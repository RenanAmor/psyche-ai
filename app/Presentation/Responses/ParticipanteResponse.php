<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\ParticipanteDTO;

/**
 * Projeta ParticipanteDTO para o campo "data" do envelope JSON — nunca
 * expõe hash de senha, que já não existe em ParticipanteDTO desde a
 * fronteira de Application.
 */
final class ParticipanteResponse
{
    private function __construct(
        private readonly ParticipanteDTO $dto
    ) {
    }

    public static function fromDTO(ParticipanteDTO $dto): self
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
            'email' => $this->dto->email,
        ];
    }
}
