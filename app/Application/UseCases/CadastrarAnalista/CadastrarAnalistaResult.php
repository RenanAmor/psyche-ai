<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\CadastrarAnalista;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Application\DTOs\AnalistaDTO;
use PsycheAI\Domain\Entities\Analista;

final class CadastrarAnalistaResult implements ResultInterface
{
    public function __construct(
        private readonly Analista $analista
    ) {
    }

    public function analista(): Analista
    {
        return $this->analista;
    }

    public function dto(): AnalistaDTO
    {
        return AnalistaDTO::fromEntity($this->analista);
    }
}
