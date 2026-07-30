<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\ValueObjects\OcorrenciaRecorrencia;

final class OcorrenciaCircuitoDTO
{
    public function __construct(
        public readonly string $sessaoId,
        public readonly string $discursoId,
        public readonly string $eventoId,
        public readonly string $momento,
        public readonly int $posicao
    ) {
    }

    public static function fromEntity(OcorrenciaRecorrencia $ocorrencia): self
    {
        return new self(
            sessaoId: $ocorrencia->sessaoId(),
            discursoId: $ocorrencia->discursoId(),
            eventoId: $ocorrencia->eventoId(),
            momento: $ocorrencia->momento()->format('Y-m-d H:i:s'),
            posicao: $ocorrencia->posicao()
        );
    }
}
