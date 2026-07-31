<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\Analista;

/**
 * Nunca expõe `senhaHash` — só o que a API/Web têm legítimo motivo para
 * ver fora da fronteira de Domínio.
 */
final class AnalistaDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $criadoEm
    ) {
    }

    public static function fromEntity(Analista $analista): self
    {
        return new self(
            id: $analista->id()->valor(),
            email: $analista->email()->valor(),
            criadoEm: $analista->criadoEm()->format(DATE_ATOM)
        );
    }
}
