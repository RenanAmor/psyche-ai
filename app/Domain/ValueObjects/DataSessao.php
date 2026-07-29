<?php

declare(strict_types=1);

namespace PsycheAI\Domain\ValueObjects;

use DateTimeImmutable;

final class DataSessao extends ValueObject
{
    public function __construct(
        private readonly DateTimeImmutable $valor
    ) {
    }

    public function valor(): DateTimeImmutable
    {
        return $this->valor;
    }

    public function __toString(): string
    {
        return $this->valor->format('Y-m-d H:i:s');
    }
}