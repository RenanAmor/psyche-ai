<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal;

use PsycheAI\Application\Contracts\CommandInterface;
use PsycheAI\Domain\Entities\Sujeito;

final class ConstruirMemoriaLongitudinalCommand implements CommandInterface
{
    public function __construct(
        public readonly Sujeito $sujeito,
        public readonly string $memoriaId
    ) {
    }
}
