<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarAnotacaoSessao;

use PsycheAI\Application\Contracts\CommandInterface;

final class RegistrarAnotacaoSessaoCommand implements CommandInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $sessaoId,
        public readonly string $conteudo
    ) {
    }
}
