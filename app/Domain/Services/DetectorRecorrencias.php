<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Services;

use PsycheAI\Domain\Contracts\DomainServiceInterface;
use PsycheAI\Domain\Entities\EventoDiscursivo;

final class DetectorRecorrencias implements DomainServiceInterface
{
    /**
     * @param EventoDiscursivo[] $eventos
     * @return array<string,int>
     */
    public function detectar(array $eventos): array
    {
        $recorrencias = [];

        foreach ($eventos as $evento) {
            $conteudo = $evento->conteudo()->valor();

            if (!isset($recorrencias[$conteudo])) {
                $recorrencias[$conteudo] = 0;
            }

            $recorrencias[$conteudo]++;
        }

        return $recorrencias;
    }
}