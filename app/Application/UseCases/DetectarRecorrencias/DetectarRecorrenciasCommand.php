<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\DetectarRecorrencias;

use PsycheAI\Application\Contracts\CommandInterface;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;

final class DetectarRecorrenciasCommand implements CommandInterface
{
    public function __construct(
        public readonly MemoriaLongitudinal $memoria,
        public readonly ?int $minimo = null
    ) {
    }
}
