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
     * @param string[] $colunasComHtml chaves cujo conteúdo já é HTML de confiança (gerado por
     *                                 Components desta camada, nunca por entrada do usuário) e
     *                                 não deve ser escapado — ex.: a coluna de ações de uma linha
     */
    public static function render(
        array $colunas,
        array $linhas,
        string $mensagemVazio = 'Nenhum registro encontrado.',
        array $colunasComHtml = []
    ): string {
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
                $valor = (string) ($linha[$chave] ?? '');
                $corpo .= sprintf(
                    '<td>%s</td>',
                    in_array($chave, $colunasComHtml, true) ? $valor : Html::e($valor)
                );
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
