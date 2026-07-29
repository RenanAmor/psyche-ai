<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Specifications;

use PsycheAI\Domain\Entities\Recorrencia;

final class RecorrenciaMinimaSpecification
{
    public function __construct(
        private readonly int $minimo = 2
    ) {
    }

    public function isSatisfiedBy(Recorrencia $recorrencia): bool
    {
        return $recorrencia->frequencia() >= $this->minimo;
    }
}