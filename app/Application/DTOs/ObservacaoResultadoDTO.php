<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

final class ObservacaoResultadoDTO
{
    /**
     * @param RecorrenciaDTO[] $recorrencias
     * @param ObservacaoDTO[] $observacoes
     */
    public function __construct(
        public readonly string $sujeitoId,
        public readonly array $recorrencias,
        public readonly array $observacoes
    ) {
    }
}
