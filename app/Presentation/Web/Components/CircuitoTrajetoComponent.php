<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Components;

use PsycheAI\Presentation\Web\ViewModels\CircuitoRecorrenciaViewModel;

/**
 * Lista, por Recorrencia, o trajeto cronológico "Sessão {data} → Sessão
 * {data} → …" (revisão pós-Sprint 16, "mapear a pulsão, todo o
 * caminho") — estende a tela do Discourse Engine (Sprint 14) sem
 * bifurcá-la.
 */
final class CircuitoTrajetoComponent
{
    /**
     * @param CircuitoRecorrenciaViewModel[] $circuitos
     */
    public static function render(array $circuitos, string $mensagemVazio = 'Nenhum circuito encontrado.'): string
    {
        if ($circuitos === []) {
            return EmptyStateComponent::render($mensagemVazio);
        }

        $itens = '';

        foreach ($circuitos as $circuito) {
            $rotulo = $circuito->rotuloLacaniano !== null
                ? sprintf(' <span class="rotulo-lacaniano">(%s)</span>', Html::e($circuito->rotuloLacaniano))
                : '';

            $fundamentacao = $circuito->fundamentacaoTeorica !== null
                ? sprintf(' <small class="fundamentacao-lacaniana">%s</small>', Html::e($circuito->fundamentacaoTeorica))
                : '';

            $itens .= sprintf(
                '<li><strong>%s</strong>: %s%s%s</li>',
                Html::e($circuito->descricao),
                Html::e($circuito->trajeto()),
                $rotulo,
                $fundamentacao
            );
        }

        return sprintf('<ul class="lista-circuitos">%s</ul>', $itens);
    }
}
