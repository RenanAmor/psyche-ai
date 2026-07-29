<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Contracts;

interface DomainEventInterface
{
    public function occurredOn(): \DateTimeImmutable;
}