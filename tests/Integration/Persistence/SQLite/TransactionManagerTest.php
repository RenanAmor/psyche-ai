<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite;

use PsycheAI\Infrastructure\Persistence\SQLite\TransactionManager;
use RuntimeException;

final class TransactionManagerTest extends SQLiteTestCase
{
    public function testCommitPersisteAsAlteracoes(): void
    {
        $transacao = new TransactionManager($this->pdo);

        $transacao->begin();
        $this->pdo->exec("INSERT INTO sujeitos (id, nome) VALUES ('1', 'Sujeito Um')");
        $transacao->commit();

        $statement = $this->pdo->query("SELECT COUNT(*) FROM sujeitos WHERE id = '1'");

        self::assertSame(1, (int) $statement->fetchColumn());
    }

    public function testRollbackDescartaAsAlteracoes(): void
    {
        $transacao = new TransactionManager($this->pdo);

        $transacao->begin();
        $this->pdo->exec("INSERT INTO sujeitos (id, nome) VALUES ('1', 'Sujeito Um')");
        $transacao->rollback();

        $statement = $this->pdo->query("SELECT COUNT(*) FROM sujeitos WHERE id = '1'");

        self::assertSame(0, (int) $statement->fetchColumn());
    }

    public function testNaoPermiteIniciarTransacaoJaEmAndamento(): void
    {
        $transacao = new TransactionManager($this->pdo);
        $transacao->begin();

        $this->expectException(RuntimeException::class);

        $transacao->begin();
    }

    public function testNaoPermiteCommitSemTransacaoEmAndamento(): void
    {
        $transacao = new TransactionManager($this->pdo);

        $this->expectException(RuntimeException::class);

        $transacao->commit();
    }

    public function testNaoPermiteRollbackSemTransacaoEmAndamento(): void
    {
        $transacao = new TransactionManager($this->pdo);

        $this->expectException(RuntimeException::class);

        $transacao->rollback();
    }
}
