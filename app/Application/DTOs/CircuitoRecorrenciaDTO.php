<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\Recorrencia;
use PsycheAI\Domain\ValueObjects\OcorrenciaRecorrencia;

final class CircuitoRecorrenciaDTO
{
    /**
     * @param OcorrenciaCircuitoDTO[] $ocorrencias
     */
    public function __construct(
        public readonly string $id,
        public readonly string $descricao,
        public readonly int $frequencia,
        public readonly array $ocorrencias,
        public readonly ?string $rotuloLacaniano = null,
        public readonly ?string $fundamentacaoTeorica = null
    ) {
    }

    /**
     * @param OcorrenciaRecorrencia[] $ocorrencias
     */
    public static function fromRecorrencia(
        Recorrencia $recorrencia,
        array $ocorrencias,
        ?string $rotuloLacaniano = null,
        ?string $fundamentacaoTeorica = null
    ): self {
        return new self(
            id: $recorrencia->id()->valor(),
            descricao: $recorrencia->descricao()->valor(),
            frequencia: $recorrencia->frequencia()->valor(),
            ocorrencias: array_map(OcorrenciaCircuitoDTO::fromEntity(...), $ocorrencias),
            rotuloLacaniano: $rotuloLacaniano,
            fundamentacaoTeorica: $fundamentacaoTeorica
        );
    }
}
