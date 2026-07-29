<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

/**
 * Porta de geração de identificadores únicos, consumida onde o Domínio
 * precisa de um novo `Identificador` sem conhecer o algoritmo (UUID v4,
 * ULID, etc.) usado para gerá-lo.
 */
interface UuidGeneratorInterface
{
    public function generate(): string;
}
