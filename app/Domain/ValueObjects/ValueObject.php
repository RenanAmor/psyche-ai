<?php

declare(strict_types=1);

namespace PsycheAI\Domain\ValueObjects;

abstract class ValueObject
{
    final public function equals(ValueObject $other): bool
    {
        return static::class === $other::class
            && $this == $other;
    }
}