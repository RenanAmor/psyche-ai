<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarChamadaSessao;

use DateTimeImmutable;
use PsycheAI\Application\Contracts\CommandInterface;

final class RegistrarChamadaSessaoCommand implements CommandInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $sessaoId,
        public readonly string $salaProvedorId,
        public readonly string $salaUrl,
        public readonly string $tokenAcesso,
        public readonly DateTimeImmutable $criadaEm,
        public readonly DateTimeImmutable $expiraEm
    ) {
    }
}
