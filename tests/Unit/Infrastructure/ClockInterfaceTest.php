<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Contracts\ClockInterface;

final class ClockInterfaceTest extends TestCase
{
    public function testImplementationReturnsAFixedInstant(): void
    {
        $instant = new DateTimeImmutable('2026-07-29T12:00:00+00:00');

        $clock = new class ($instant) implements ClockInterface {
            public function __construct(private readonly DateTimeImmutable $instant)
            {
            }

            public function now(): DateTimeImmutable
            {
                return $this->instant;
            }
        };

        $this->assertSame($instant, $clock->now());
    }
}
