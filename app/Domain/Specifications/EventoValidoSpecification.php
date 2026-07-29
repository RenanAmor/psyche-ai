<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Specifications;

use PsycheAI\Domain\Entities\EventoDiscursivo;

final class EventoValidoSpecification
{
    public function isSatisfiedBy(EventoDiscursivo $evento): bool
    {
        return trim($evento->conteudo()->valor()) !== '';
    }
}