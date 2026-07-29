<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\CadastrarSujeito;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Application\DTOs\SujeitoDTO;
use PsycheAI\Domain\Entities\Sujeito;

final class CadastrarSujeitoResult implements ResultInterface
{
    public function __construct(
        private readonly Sujeito $sujeito
    ) {
    }

    public function sujeito(): Sujeito
    {
        return $this->sujeito;
    }

    public function dto(): SujeitoDTO
    {
        return SujeitoDTO::fromEntity($this->sujeito);
    }
}
