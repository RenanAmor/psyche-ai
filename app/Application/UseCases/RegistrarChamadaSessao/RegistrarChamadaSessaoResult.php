<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarChamadaSessao;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Application\DTOs\ChamadaSessaoDTO;
use PsycheAI\Domain\Entities\ChamadaSessao;

final class RegistrarChamadaSessaoResult implements ResultInterface
{
    public function __construct(
        private readonly ChamadaSessao $chamada
    ) {
    }

    public function chamada(): ChamadaSessao
    {
        return $this->chamada;
    }

    public function dto(): ChamadaSessaoDTO
    {
        return ChamadaSessaoDTO::fromEntity($this->chamada);
    }
}
