<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\DetectarCircuitoRecorrencia;

use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Domain\Services\DetectorRecorrencias;

final class DetectarCircuitoRecorrenciaHandler implements UseCaseInterface
{
    public function __construct(
        private readonly DetectorRecorrencias $detectorRecorrencias = new DetectorRecorrencias()
    ) {
    }

    public function handle(DetectarCircuitoRecorrenciaCommand $command): DetectarCircuitoRecorrenciaResult
    {
        $ocorrenciasPorConteudo = $this->detectorRecorrencias->detectarCircuito($command->memoria);

        $circuitosPorRecorrencia = [];

        foreach ($command->recorrencias as $recorrencia) {
            $conteudo = $recorrencia->descricao()->valor();

            $circuitosPorRecorrencia[$recorrencia->id()->valor()] = $ocorrenciasPorConteudo[$conteudo] ?? [];
        }

        return new DetectarCircuitoRecorrenciaResult($circuitosPorRecorrencia);
    }
}
