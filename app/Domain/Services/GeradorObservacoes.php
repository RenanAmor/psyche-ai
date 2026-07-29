<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Services;

use PsycheAI\Domain\Contracts\DomainServiceInterface;
use PsycheAI\Domain\Entities\Observacao;
use PsycheAI\Domain\Entities\Recorrencia;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\ObservacaoTexto;

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
                new Identificador((string) ($indice + 1)),
                new ObservacaoTexto(sprintf(
                    'Recorrência observada: %s (%d ocorrência(s)).',
                    $recorrencia->descricao()->valor(),
                    $recorrencia->frequencia()->valor()
                ))
            );
        }

        return $observacoes;
    }
}