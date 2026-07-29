<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Components;

final class AlertComponent
{
    private const TIPOS_VALIDOS = ['sucesso', 'erro', 'aviso', 'info'];

    public static function render(string $mensagem, string $tipo = 'info'): string
    {
        if (!in_array($tipo, self::TIPOS_VALIDOS, true)) {
            $tipo = 'info';
        }

        return sprintf(
            '<div class="alerta alerta-%s" role="alert">%s</div>',
            Html::e($tipo),
            Html::e($mensagem)
        );
    }
}
