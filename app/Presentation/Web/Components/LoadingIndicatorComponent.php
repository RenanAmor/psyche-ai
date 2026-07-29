<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Components;

final class LoadingIndicatorComponent
{
    public static function render(string $rotulo = 'Carregando...'): string
    {
        return sprintf(
            '<div class="indicador-carregamento" role="status" aria-live="polite"><span class="spinner"></span> %s</div>',
            Html::e($rotulo)
        );
    }
}
