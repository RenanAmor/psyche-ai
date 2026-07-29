<?php

declare(strict_types=1);

namespace PsycheAI\Domain\ValueObjects;

use InvalidArgumentException;

final class Posicao extends ValueObject
{
    public function __construct(
        private readonly int $valor
    ) {
        if ($valor < 0) {
            throw new InvalidArgumentException('A posição não pode ser negativa.');
        }
    }

    public function valor(): int
    {
        return $this->valor;
    }
}