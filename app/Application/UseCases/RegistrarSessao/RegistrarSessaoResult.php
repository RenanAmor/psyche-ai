<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarSessao;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Application\DTOs\SessaoDTO;
use PsycheAI\Domain\Entities\Sessao;

final class RegistrarSessaoResult implements ResultInterface
{
    public function __construct(
        private readonly Sessao $sessao
    ) {
    }

    public function sessao(): Sessao
    {
        return $this->sessao;
    }

    public function dto(): SessaoDTO
    {
        return SessaoDTO::fromEntity($this->sessao);
    }
}
