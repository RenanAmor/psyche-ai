<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

/**
 * Porta de acesso ao tempo corrente, permitindo que Domínio e Aplicação
 * dependam de uma abstração em vez de `new \DateTimeImmutable()` diretamente.
 */
interface ClockInterface
{
    public function now(): \DateTimeImmutable;
}
