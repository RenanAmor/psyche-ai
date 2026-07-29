<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite;

use PDO;
use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Persistence\SQLite\Connection;
use PsycheAI\Infrastructure\Persistence\SQLite\Migrations\MigrationRunner;

abstract class SQLiteTestCase extends TestCase
{
    protected PDO $pdo;

    protected function setUp(): void
    {
        $connection = new Connection(':memory:');
        $this->pdo = $connection->pdo();

        MigrationRunner::comMigrationsPadrao($this->pdo)->run();
    }
}
