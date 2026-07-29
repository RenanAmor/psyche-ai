<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Components;

/**
 * Tabela genérica orientada a dados. Quando não há linhas, devolve o
 * EmptyStateComponent no lugar da tabela — nenhuma página precisa
 * verificar isso manualmente.
 */
final class TableComponent
{
    /**
     * @param array<string, string> $colunas chave da linha => rótulo do cabeçalho
     * @param array<int, array<string, mixed>> $linhas
     */
    public static function render(array $colunas, array $linhas, string $mensagemVazio = 'Nenhum registro encontrado.'): string
    {
        if ($linhas === []) {
            return EmptyStateComponent::render($mensagemVazio);
        }

        $cabecalho = '';
        foreach ($colunas as $rotulo) {
            $cabecalho .= sprintf('<th>%s</th>', Html::e($rotulo));
        }

        $corpo = '';
        foreach ($linhas as $linha) {
            $corpo .= '<tr>';
            foreach (array_keys($colunas) as $chave) {
                $corpo .= sprintf('<td>%s</td>', Html::e((string) ($linha[$chave] ?? '')));
            }
            $corpo .= '</tr>';
        }

        return sprintf(
            '<table class="tabela"><thead><tr>%s</tr></thead><tbody>%s</tbody></table>',
            $cabecalho,
            $corpo
        );
    }
}
