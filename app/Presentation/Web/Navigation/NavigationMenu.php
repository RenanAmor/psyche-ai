<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Navigation;

/**
 * Fonte única dos itens do menu lateral (superfície do Analista, atrás de
 * `PortaoDeAnalista`). Tanto a Sidebar quanto o Router/Routes desta Sprint
 * devem apontar para as mesmas seis seções definidas aqui, evitando que a
 * navegação e as rotas divirjam. "Conversa" não faz parte deste menu: a
 * ECO (`/conversa*`) agora exige uma conta de Participante — um sistema de
 * autenticação independente do Analista (`PortaoDeParticipante`) — então um
 * link aqui levaria o Analista a uma tela de login à qual ele nunca tem
 * conta, por decisão de arquitetura.
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
