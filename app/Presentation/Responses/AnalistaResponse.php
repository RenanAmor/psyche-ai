<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\AnalistaDTO;

/**
 * Projeta AnalistaDTO para o campo "data" do envelope JSON — nunca expõe
 * hash de senha, que já não existe em AnalistaDTO desde a fronteira de
 * Application.
 */
final class AnalistaResponse
{
    private function __construct(
        private readonly AnalistaDTO $dto
    ) {
    }

    public static function fromDTO(AnalistaDTO $dto): self
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
