<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Components;

final class EmptyStateComponent
{
    public static function render(string $mensagem = 'Nenhum registro encontrado.'): string
    {
        return sprintf(
            '<div class="estado-vazio">%s</div>',
            Html::e($mensagem)
        );
    }
}
