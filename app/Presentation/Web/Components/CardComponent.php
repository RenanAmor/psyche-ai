<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Components;

final class CardComponent
{
    public static function render(string $titulo, int|string $valor, string $descricao = '', string $icone = ''): string
    {
        return sprintf(
            '<div class="cartao" data-icone="%s"><span class="cartao-titulo">%s</span>'
                . '<strong class="cartao-valor">%s</strong><span class="cartao-descricao">%s</span></div>',
            Html::e($icone),
            Html::e($titulo),
            Html::e($valor),
            Html::e($descricao)
        );
    }
}
