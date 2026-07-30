<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\ClassificarFormacaoFreudiana;

use PsycheAI\Application\Contracts\CommandInterface;

final class ClassificarFormacaoFreudianaCommand implements CommandInterface
{
    public function __construct(
        public readonly string $conteudo
    ) {
    }
}
