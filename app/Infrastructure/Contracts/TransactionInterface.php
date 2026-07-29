<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

/**
 * Porta de controle transacional (Unit of Work) para operações que
 * precisam ser atômicas através de múltiplas escritas de persistência.
 */
interface TransactionInterface
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
