<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\DetectarCircuitoRecorrencia;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Domain\ValueObjects\OcorrenciaRecorrencia;

final class DetectarCircuitoRecorrenciaResult implements ResultInterface
{
    /**
     * @param array<string, OcorrenciaRecorrencia[]> $circuitosPorRecorrencia id da Recorrencia => ocorrências em ordem cronológica
     */
    public function __construct(
        private readonly array $circuitosPorRecorrencia
    ) {
    }

    /**
     * @return array<string, OcorrenciaRecorrencia[]>
     */
    public function circuitosPorRecorrencia(): array
    {
        return $this->circuitosPorRecorrencia;
    }
}
