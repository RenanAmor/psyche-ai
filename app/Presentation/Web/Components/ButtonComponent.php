<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Components;

final class ButtonComponent
{
    private const VARIANTES_VALIDAS = ['primario', 'secundario', 'perigo'];

    public static function link(string $rotulo, string $href, string $variante = 'primario'): string
    {
        return sprintf(
            '<a class="botao botao-%s" href="%s">%s</a>',
            self::variante($variante),
            Html::e($href),
            Html::e($rotulo)
        );
    }

    public static function submit(string $rotulo, string $variante = 'primario'): string
    {
        return sprintf(
            '<button type="submit" class="botao botao-%s">%s</button>',
            self::variante($variante),
            Html::e($rotulo)
        );
    }

    private static function variante(string $variante): string
    {
        return in_array($variante, self::VARIANTES_VALIDAS, true) ? $variante : 'primario';
    }
}
