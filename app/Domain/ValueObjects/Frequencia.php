<?php

declare(strict_types=1);

namespace PsycheAI\Domain\ValueObjects;

use InvalidArgumentException;

final class Frequencia extends ValueObject
{
    public function __construct(
        private readonly int $valor
    ) {
        if ($valor < 1) {
            throw new InvalidArgumentException(
                'A frequência deve ser maior que zero.'
            );
        }
    }

    public function valor(): int
    {
        return $this->valor;
    }

    public function incrementar(): self
    {
        return new self($this->valor + 1);
    }
}