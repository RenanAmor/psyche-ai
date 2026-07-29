<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Navigation;

final class NavigationItem
{
    public function __construct(
        public readonly string $rotulo,
        public readonly string $rota,
        public readonly string $icone
    ) {
    }

    public function ativo(string $rotaAtual): bool
    {
        return $this->rota === $rotaAtual;
    }
}
