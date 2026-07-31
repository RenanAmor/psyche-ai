<?php

declare(strict_types=1);

namespace PsycheAI\Domain\ValueObjects;

use InvalidArgumentException;

final class Email extends ValueObject
{
    public function __construct(
        private readonly string $valor
    ) {
        if (filter_var($valor, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException(
                sprintf('"%s" não é um e-mail válido.', $valor)
            );
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
