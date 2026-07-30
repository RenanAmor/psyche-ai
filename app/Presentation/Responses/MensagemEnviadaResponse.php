<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\MensagemEnviadaDTO;

final class MensagemEnviadaResponse
{
    private function __construct(
        private readonly MensagemEnviadaDTO $dto
    ) {
    }

    public static function fromDTO(MensagemEnviadaDTO $dto): self
    {
        return new self($dto);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'mensagemUsuario' => EventoDiscursivoResponse::fromDTO($this->dto->mensagemUsuario)->toArray(),
            'respostaSistema' => EventoDiscursivoResponse::fromDTO($this->dto->respostaSistema)->toArray(),
        ];
    }
}
