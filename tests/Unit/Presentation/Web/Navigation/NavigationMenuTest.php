<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Navigation;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Navigation\NavigationMenu;

final class NavigationMenuTest extends TestCase
{
    public function testContemAsSeisSecoesExigidasNaOrdem(): void
    {
        $rotulos = array_map(static fn ($item) => $item->rotulo, NavigationMenu::itens());

        $this->assertSame(
            ['Dashboard', 'Sujeitos', 'Sessões', 'Discursos', 'Memórias', 'Eventos Discursivos'],
            $rotulos
        );
    }

    public function testCadaItemMarcaSeAtivoApenasParaSuaPropriaRota(): void
    {
        foreach (NavigationMenu::itens() as $item) {
            $this->assertTrue($item->ativo($item->rota));
            $this->assertFalse($item->ativo('/rota-diferente-' . $item->rota));
        }
    }
}
