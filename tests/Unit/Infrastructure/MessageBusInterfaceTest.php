<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Contracts\MessageBusInterface;

final class MessageBusInterfaceTest extends TestCase
{
    public function testImplementationDispatchesAMessageAndReturnsAResult(): void
    {
        $bus = new class implements MessageBusInterface {
            public function dispatch(object $message): mixed
            {
                return get_class($message);
            }
        };

        $result = $bus->dispatch(new class {
        });

        $this->assertIsString($result);
    }
}
