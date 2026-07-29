<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\DetectarRecorrencias;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Application\DTOs\RecorrenciaDTO;
use PsycheAI\Domain\Entities\Recorrencia;

final class DetectarRecorrenciasResult implements ResultInterface
{
    /**
     * @param Recorrencia[] $recorrencias
     */
    public function __construct(
        private readonly array $recorrencias
    ) {
    }

    /**
     * @return Recorrencia[]
     */
    public function recorrencias(): array
    {
        return $this->recorrencias;
    }

    /**
     * @return RecorrenciaDTO[]
     */
    public function dtos(): array
    {
        return array_map(
            static fn (Recorrencia $recorrencia) => RecorrenciaDTO::fromEntity($recorrencia),
            $this->recorrencias
        );
    }
}
