<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

final class CircuitoResultadoDTO
{
    /**
     * @param CircuitoRecorrenciaDTO[] $circuitos
     */
    public function __construct(
        public readonly string $sujeitoId,
        public readonly array $circuitos
    ) {
    }
}
