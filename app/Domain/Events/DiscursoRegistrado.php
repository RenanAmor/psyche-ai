<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Events;

use DateTimeImmutable;
use PsycheAI\Domain\Contracts\DomainEventInterface;

final class DiscursoRegistrado implements DomainEventInterface
{
    public function __construct(
        private readonly string $discursoId,
        private readonly DateTimeImmutable $occurredOn = new DateTimeImmutable()
    ) {
    }

    public function discursoId(): string
    {
        return $this->discursoId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}