<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarDiscurso;

use PsycheAI\Application\Contracts\CommandInterface;
use PsycheAI\Domain\Entities\Sessao;

final class RegistrarDiscursoCommand implements CommandInterface
{
    public function __construct(
        public readonly Sessao $sessao,
        public readonly string $id,
        public readonly string $conteudo
    ) {
    }
}
