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
 * duplicar a montagem em dois lugares.
 *
 * O histórico é renderizado como bolhas de mensagem (não uma tabela
 * genérica): a tela de Conversa é uma experiência de chat, e alinhar cada
 * mensagem à esquerda/direita conforme o autor exige um contêiner por
 * mensagem em vez de linhas de tabela.
 */
final class ConversaAreaComponent
{
    /**
     * @param MensagemViewModel[] $mensagens
     */
    public static function render(array $mensagens, ?string $alerta, string $tipoAlerta): string
    {
        $alertaHtml = $alerta !== null ? AlertComponent::render($alerta, $tipoAlerta) : '';

        $historicoHtml = $mensagens === []
            ? EmptyStateComponent::render('Nenhuma mensagem ainda. Escreva abaixo para começar.')
            : implode('', array_map(self::renderMensagem(...), $mensagens));

        return $alertaHtml . sprintf('<div id="historico-mensagens" class="historico-mensagens">%s</div>', $historicoHtml);
    }

    private static function renderMensagem(MensagemViewModel $mensagem): string
    {
        $classeAutor = $mensagem->autor === 'Você' ? 'mensagem-voce' : 'mensagem-sistema';

        return sprintf(
            '<div class="mensagem %s"><div class="mensagem-bolha">'
                . '<span class="mensagem-autor">%s</span>'
                . '<p class="mensagem-conteudo">%s</p>'
                . '<time class="mensagem-quando">%s</time>'
                . '</div></div>',
            $classeAutor,
            Html::e($mensagem->autor),
            nl2br(Html::e($mensagem->conteudo)),
            Html::e($mensagem->criadoEm)
        );
    }
}
