<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Events;

use DateTimeImmutable;
use PsycheAI\Domain\Contracts\DomainEventInterface;

final class SessaoRegistrada implements DomainEventInterface
{
    public function __construct(
        private readonly string $sessaoId,
        private readonly DateTimeImmutable $occurredOn = new DateTimeImmutable()
    ) {
    }

    public function sessaoId(): string
    {
        return $this->sessaoId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}