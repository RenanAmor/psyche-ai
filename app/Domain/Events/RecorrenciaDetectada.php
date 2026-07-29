<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Events;

use DateTimeImmutable;
use PsycheAI\Domain\Contracts\DomainEventInterface;

final class RecorrenciaDetectada implements DomainEventInterface
{
    public function __construct(
        private readonly string $recorrenciaId,
        private readonly DateTimeImmutable $occurredOn = new DateTimeImmutable()
    ) {
    }

    public function recorrenciaId(): string
    {
        return $this->recorrenciaId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}