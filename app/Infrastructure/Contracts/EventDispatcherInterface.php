<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

use PsycheAI\Domain\Contracts\DomainEventInterface;

/**
 * Porta de despacho dos Eventos de Domínio (`DomainEventInterface`) para
 * consumidores externos (ex.: listeners, filas, integrações).
 */
interface EventDispatcherInterface
{
    public function dispatch(DomainEventInterface $event): void;

    /**
     * @param iterable<DomainEventInterface> $events
     */
    public function dispatchAll(iterable $events): void;
}
