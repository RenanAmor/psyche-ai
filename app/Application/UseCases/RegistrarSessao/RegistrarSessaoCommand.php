<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarSessao;

use DateTimeImmutable;
use PsycheAI\Application\Contracts\CommandInterface;
use PsycheAI\Domain\Entities\Sujeito;

final class RegistrarSessaoCommand implements CommandInterface
{
    public function __construct(
        public readonly Sujeito $sujeito,
        public readonly string $id,
        public readonly DateTimeImmutable $data
    ) {
    }
}
