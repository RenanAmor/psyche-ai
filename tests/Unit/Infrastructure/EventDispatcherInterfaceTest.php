<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Contracts\DomainEventInterface;
use PsycheAI\Domain\Events\DiscursoRegistrado;
use PsycheAI\Infrastructure\Contracts\EventDispatcherInterface;

final class EventDispatcherInterfaceTest extends TestCase
{
    public function testImplementationDispatchesASingleDomainEvent(): void
    {
        $dispatcher = new class implements EventDispatcherInterface {
            /** @var array<int, DomainEventInterface> */
            public array $dispatched = [];

            public function dispatch(DomainEventInterface $event): void
            {
                $this->dispatched[] = $event;
            }

            public function dispatchAll(iterable $events): void
            {
                foreach ($events as $event) {
                    $this->dispatch($event);
                }
            }
        };

        $evento = new DiscursoRegistrado('1');
        $dispatcher->dispatch($evento);

        $this->assertSame([$evento], $dispatcher->dispatched);
    }

    public function testImplementationDispatchesMultipleDomainEvents(): void
    {
        $dispatcher = new class implements EventDispatcherInterface {
            /** @var array<int, DomainEventInterface> */
            public array $dispatched = [];

            public function dispatch(DomainEventInterface $event): void
            {
                $this->dispatched[] = $event;
            }

            public function dispatchAll(iterable $events): void
            {
                foreach ($events as $event) {
                    $this->dispatch($event);
                }
            }
        };

        $eventos = [new DiscursoRegistrado('1'), new DiscursoRegistrado('2')];
        $dispatcher->dispatchAll($eventos);

        $this->assertSame($eventos, $dispatcher->dispatched);
    }
}
