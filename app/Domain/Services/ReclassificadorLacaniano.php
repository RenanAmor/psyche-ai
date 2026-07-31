<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Services;

use PsycheAI\Domain\Contracts\DomainServiceInterface;
use PsycheAI\Domain\Entities\Recorrencia;
use PsycheAI\Domain\ValueObjects\OcorrenciaRecorrencia;
use PsycheAI\Domain\ValueObjects\TipoFormacaoFreudiana;

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
    private const ROTULO_METAFORA = 'Estrutura candidata: metáfora — condensação.';
    private const ROTULO_FORMACAO_DE_COMPROMISSO = 'Estrutura candidata: formação de compromisso — a determinar entre metáfora e metonímia.';

    /**
     * Fundamentação teórica de cada rótulo (Regra 11, Regras-Dominio.md):
     * a regra da ontologia que gerou o rótulo, nunca uma leitura do que
     * ele significaria para o sujeito específico — isso permanece
     * exclusivo do analista (Regra 10). Exposta apenas nas telas do
     * analista, nunca na conversa do sujeito (Documento-Mestre.md §6.7).
     * Tabela de lookup determinística, sem LLM — mesmo princípio de
     * reclassificarPorTipoFreudiano().
     */
    private const FUNDAMENTACAO = [
        self::ROTULO => 'Deslize metonímico: o tema desliza dentro do próprio discurso, sem substituição entre conteúdos distintos (Ontologia-Lacan.md §3.4/§4).',
        self::ROTULO_CIRCUITO => 'Circuito: o mesmo conteúdo normalizado reaparece em ≥2 sessões distintas — leitura de Repetição (Ontologia-Freud.md) como Real, o que insiste e retorna ao mesmo lugar (Ontologia-Lacan.md §3.7/§4).',
        self::ROTULO_METAFORA => 'Metáfora: Chiste e Sonho são as vias em que Freud localiza a condensação, relida por Lacan como substituição de um significante por outro (Ontologia-Lacan.md §3.3/§4).',
        self::ROTULO_FORMACAO_DE_COMPROMISSO => 'Formação de compromisso é a categoria geral (Ontologia-Freud.md §3.5) e não permite decidir entre metáfora e metonímia sem mais informação — a fundamentação reflete essa indeterminação, nunca a resolve por conta própria.',
    ];

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

    /**
     * Extensão aditiva (revisão pós-Sprint 17, classificação estrutural via
     * LLM no Motor Freud): traduz o TipoFormacaoFreudiana já classificado
     * por outra parte do sistema (nunca por este método — ele não analisa
     * dado novo, mesmo princípio de reclassificar()) para o vocabulário
     * lacaniano, seguindo a tabela de correspondência já documentada em
     * Ontologia-Lacan.md §4: Chiste e Sonhos são as vias em que Freud
     * localiza a condensação, relida por Lacan como metáfora; Ato falho e
     * Repetição são vias de deslocamento, relido como metonímia. Formação
     * de compromisso, por ser a categoria geral (Ontologia-Freud.md §3.5),
     * não permite decidir entre as duas sem mais informação — o rótulo
     * reflete essa indeterminação, nunca a resolve por conta própria.
     *
     * Puramente uma tabela de lookup determinística — nenhuma chamada a
     * LLM entra no Lacan Engine.
     */
    public function reclassificarPorTipoFreudiano(TipoFormacaoFreudiana $tipo): string
    {
        return match ($tipo) {
            TipoFormacaoFreudiana::Chiste, TipoFormacaoFreudiana::Sonho => self::ROTULO_METAFORA,
            TipoFormacaoFreudiana::AtoFalho, TipoFormacaoFreudiana::Repeticao => self::ROTULO,
            TipoFormacaoFreudiana::FormacaoDeCompromisso => self::ROTULO_FORMACAO_DE_COMPROMISSO,
            TipoFormacaoFreudiana::NaoClassificado => self::ROTULO,
        };
    }

    /**
     * Fundamentação teórica de um rótulo já produzido por este serviço
     * (reclassificar()/reclassificarComTrajeto()/reclassificarPorTipoFreudiano())
     * — nunca de um rótulo arbitrário externo. Regra 11
     * (Regras-Dominio.md): exclusivo das telas do analista.
     */
    public function fundamentacaoPara(string $rotulo): string
    {
        return self::FUNDAMENTACAO[$rotulo] ?? '';
    }
}
