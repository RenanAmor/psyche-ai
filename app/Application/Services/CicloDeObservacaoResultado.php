<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Domain\Entities\MemoriaLongitudinal;
use PsycheAI\Domain\Entities\Observacao;
use PsycheAI\Domain\Entities\Recorrencia;

/**
 * Resultado composto do ciclo Memória → Recorrências → Observações,
 * conforme docs/Ciclo-do-Sistema.md.
 */
final class CicloDeObservacaoResultado
{
    /**
     * @param Recorrencia[] $recorrencias
     * @param Observacao[] $observacoes
     */
    public function __construct(
        private readonly MemoriaLongitudinal $memoria,
        private readonly array $recorrencias,
        private readonly array $observacoes
    ) {
    }

    public function memoria(): MemoriaLongitudinal
    {
        return $this->memoria;
    }

    /**
     * @return Recorrencia[]
     */
    public function recorrencias(): array
    {
        return $this->recorrencias;
    }

    /**
     * @return Observacao[]
     */
    public function observacoes(): array
    {
        return $this->observacoes;
    }
}
