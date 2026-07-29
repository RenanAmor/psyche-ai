<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Events;

use DateTimeImmutable;
use PsycheAI\Domain\Contracts\DomainEventInterface;

final class ObservacaoRegistrada implements DomainEventInterface
{
    public function __construct(
        private readonly string $observacaoId,
        private readonly DateTimeImmutable $occurredOn = new DateTimeImmutable()
    ) {
    }

    public function observacaoId(): string
    {
        return $this->observacaoId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}