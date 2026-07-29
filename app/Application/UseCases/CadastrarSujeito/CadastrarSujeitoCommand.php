<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\CadastrarSujeito;

use PsycheAI\Application\Contracts\CommandInterface;

final class CadastrarSujeitoCommand implements CommandInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $nome
    ) {
    }
}
