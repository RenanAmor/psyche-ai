<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Navigation;

/**
 * Fonte única dos itens do menu lateral. Tanto a Sidebar quanto o
 * Router/Routes desta Sprint devem apontar para as mesmas seis seções
 * definidas aqui, evitando que a navegação e as rotas divirjam.
 */
final class NavigationMenu
{
    /**
     * @return NavigationItem[]
     */
    public static function itens(): array
    {
        return [
            new NavigationItem('Dashboard', '/', 'grid'),
            new NavigationItem('Sujeitos', '/sujeitos', 'user'),
            new NavigationItem('Sessões', '/sessoes', 'calendar'),
            new NavigationItem('Discursos', '/discursos', 'message'),
            new NavigationItem('Memórias', '/memorias', 'archive'),
            new NavigationItem('Eventos Discursivos', '/eventos-discursivos', 'activity'),
        ];
    }
}
