<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\CadastrarParticipante;

use PsycheAI\Application\Contracts\CommandInterface;

final class CadastrarParticipanteCommand implements CommandInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $senha
    ) {
    }
}
