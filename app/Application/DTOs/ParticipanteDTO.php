<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\Participante;

/**
 * Nunca expõe `senhaHash` — só o que a API/Web têm legítimo motivo para
 * ver fora da fronteira de Domínio.
 */
final class ParticipanteDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $criadoEm
    ) {
    }

    public static function fromEntity(Participante $participante): self
    {
        return new self(
            id: $participante->id()->valor(),
            email: $participante->email()->valor(),
            criadoEm: $participante->criadoEm()->format(DATE_ATOM)
        );
    }
}
