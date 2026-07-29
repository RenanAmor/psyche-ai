<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Services;

use PsycheAI\Domain\Contracts\DomainServiceInterface;
use PsycheAI\Domain\Entities\Observacao;
use PsycheAI\Domain\Entities\Recorrencia;

final class GeradorObservacoes implements DomainServiceInterface
{
    /**
     * @param Recorrencia[] $recorrencias
     * @return Observacao[]
     */
    public function gerar(array $recorrencias): array
    {
        $observacoes = [];

        foreach ($recorrencias as $indice => $recorrencia) {
            $observacoes[] = new Observacao(
                (string) ($indice + 1),
                sprintf(
                    'Recorrência observada: %s (%d ocorrência(s)).',
                    $recorrencia->descricao(),
                    $recorrencia->frequencia()
                )
            );
        }

        return $observacoes;
    }
}