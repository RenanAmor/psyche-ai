<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Services;

use PsycheAI\Domain\Contracts\DomainServiceInterface;
use PsycheAI\Domain\Entities\Recorrencia;

/**
 * Motor Lacan (Sprint 16): não analisa dado novo algum — apenas reclassifica,
 * com vocabulário lacaniano, as mesmas Recorrencia já trazidas pelo Motor
 * Freud (repetição de conteúdo normalizado, sem substituição entre
 * conteúdos distintos). Por não haver substituição de um significante por
 * outro — apenas o mesmo conteúdo reaparecendo na cadeia —, a releitura
 * estrutural correspondente (Ontologia-Lacan.md §4) é sempre a de
 * deslocamento/metonímia, nunca a de condensação/metáfora, que pressupõe
 * dois conteúdos distintos em substituição.
 *
 * O rótulo devolvido nunca afirma o estatuto de significante confirmado:
 * é sempre uma "estrutura candidata", nos termos literais de
 * Ontologia-Lacan.md §5 — só o sujeito, no processo analítico, confirma
 * esse estatuto.
 */
final class ReclassificadorLacaniano implements DomainServiceInterface
{
    private const ROTULO = 'Estrutura candidata: deslize metonímico.';

    /**
     * @param Recorrencia[] $recorrencias
     * @return array<string,string> id da Recorrencia => rótulo lacaniano
     */
    public function reclassificar(array $recorrencias): array
    {
        $rotulos = [];

        foreach ($recorrencias as $recorrencia) {
            $rotulos[$recorrencia->id()->valor()] = self::ROTULO;
        }

        return $rotulos;
    }
}
