<?php

declare(strict_types=1);

namespace PsycheAI\Domain\ValueObjects;

use InvalidArgumentException;

final class Texto extends ValueObject
{
    public function __construct(
        private readonly string $valor
    ) {
        if (trim($valor) === '') {
            throw new InvalidArgumentException('O texto não pode ser vazio.');
        }
    }

    public function valor(): string
    {
        return $this->valor;
    }

    public function __toString(): string
    {
        return $this->valor;
    }
}