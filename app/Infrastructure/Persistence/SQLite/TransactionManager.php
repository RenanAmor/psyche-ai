<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite;

use PDO;
use PsycheAI\Infrastructure\Contracts\TransactionInterface;
use RuntimeException;

/**
 * Gerenciador de transações (Unit of Work) sobre uma conexão PDO com SQLite.
 */
final class TransactionManager implements TransactionInterface
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function begin(): void
    {
        if ($this->pdo->inTransaction()) {
            throw new RuntimeException('Uma transação já está em andamento.');
        }

        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        if (!$this->pdo->inTransaction()) {
            throw new RuntimeException('Não há transação em andamento.');
        }

        $this->pdo->commit();
    }

    public function rollback(): void
    {
        if (!$this->pdo->inTransaction()) {
            throw new RuntimeException('Não há transação em andamento.');
        }

        $this->pdo->rollBack();
    }
}
