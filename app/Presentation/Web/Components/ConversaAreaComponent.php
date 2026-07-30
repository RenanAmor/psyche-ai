<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Components;

use PsycheAI\Presentation\Web\ViewModels\MensagemViewModel;

/**
 * Alerta + histórico de mensagens da tela de Conversa, num único bloco de
 * markup. Extraído da view em duas peças (ConversaController e
 * conversa/index.php) na Sprint 17 para que o mesmo HTML sirva tanto a
 * página cheia (GET /conversa, POST /conversa/enviar) quanto o fragmento
 * JSON devolvido por POST /conversa/mensagens ao fetch() da view — sem
 * duplicar a montagem da tabela em dois lugares.
 */
final class ConversaAreaComponent
{
    /**
     * @param MensagemViewModel[] $mensagens
     */
    public static function render(array $mensagens, ?string $alerta, string $tipoAlerta): string
    {
        $linhas = array_map(
            static fn (MensagemViewModel $mensagem): array => [
                'autor' => $mensagem->autor,
                'conteudo' => $mensagem->conteudo,
                'criadoEm' => $mensagem->criadoEm,
            ],
            $mensagens
        );

        $alertaHtml = $alerta !== null ? AlertComponent::render($alerta, $tipoAlerta) : '';

        $tabelaHtml = TableComponent::render(
            ['autor' => 'Autor', 'conteudo' => 'Mensagem', 'criadoEm' => 'Quando'],
            $linhas,
            'Nenhuma mensagem ainda. Escreva abaixo para começar.'
        );

        return $alertaHtml . sprintf('<div id="historico-mensagens" class="historico-mensagens">%s</div>', $tabelaHtml);
    }
}
