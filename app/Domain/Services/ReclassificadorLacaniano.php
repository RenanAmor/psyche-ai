<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Services;

use PsycheAI\Domain\Contracts\DomainServiceInterface;
use PsycheAI\Domain\Entities\Recorrencia;
use PsycheAI\Domain\ValueObjects\OcorrenciaRecorrencia;

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
    private const ROTULO_CIRCUITO = 'Estrutura candidata: circuito — o tema retorna ao mesmo ponto através de sessões distintas.';

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

    /**
     * reclassificar() fica congelado (assinatura e saída intocadas — Sprint
     * 16). Este método é aditivo: reclassifica as mesmas Recorrencias, mas
     * cruzando com o circuito (DetectorRecorrencias::detectarCircuito(),
     * revisão pós-Sprint 16) para diferenciar duas situações que
     * reclassificar() sozinho não distingue — ambas continuam sendo apenas
     * constatações estruturais contáveis (mesmo conteúdo normalizado
     * aparecendo de novo), nunca uma leitura de sentido:
     *
     * - a recorrência aparece só dentro de uma mesma Sessao (ou os dados de
     *   circuito não estão disponíveis): mesmo rótulo de sempre, deslize
     *   metonímico — o tema desliza dentro do próprio discurso.
     * - a recorrência atravessa ≥2 Sessões distintas: rótulo de circuito —
     *   o tema retorna ao mesmo ponto através do tempo, no sentido do que
     *   Ontologia-Lacan.md §3.7/§4 formaliza como Real a partir da
     *   repetição freudiana que excede o princípio do prazer (Ontologia-
     *   Freud.md, Repetição): o que insiste e retorna ao mesmo lugar,
     *   nunca uma interpretação sobre o que esse retorno significaria.
     *
     * @param Recorrencia[] $recorrencias
     * @param array<string, OcorrenciaRecorrencia[]> $circuitos id da Recorrencia => ocorrências em ordem cronológica
     * @return array<string,string> id da Recorrencia => rótulo lacaniano
     */
    public function reclassificarComTrajeto(array $recorrencias, array $circuitos): array
    {
        $rotulos = [];

        foreach ($recorrencias as $recorrencia) {
            $id = $recorrencia->id()->valor();

            $sessoesDistintas = array_unique(array_map(
                static fn (OcorrenciaRecorrencia $ocorrencia): string => $ocorrencia->sessaoId(),
                $circuitos[$id] ?? []
            ));

            $rotulos[$id] = count($sessoesDistintas) >= 2 ? self::ROTULO_CIRCUITO : self::ROTULO;
        }

        return $rotulos;
    }
}
