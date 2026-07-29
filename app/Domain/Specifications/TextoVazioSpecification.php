<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Specifications;

final class TextoVazioSpecification
{
    public function isSatisfiedBy(string $texto): bool
    {
        return trim($texto) !== '';
    }
}