<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Components;

/**
 * Escape HTML compartilhado por todos os componentes reutilizáveis,
 * evitando repetir htmlspecialchars(..., ENT_QUOTES, 'UTF-8') em cada um.
 */
final class Html
{
    public static function e(string|int|float $valor): string
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}
