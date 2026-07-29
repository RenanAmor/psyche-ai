<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite;

use PDO;
use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Persistence\SQLite\Connection;

final class ConnectionTest extends TestCase
{
    private string $databasePath;

    protected function setUp(): void
    {
        $this->databasePath = sys_get_temp_dir()
            . DIRECTORY_SEPARATOR . 'psyche-ai-tests-' . uniqid('', true)
            . DIRECTORY_SEPARATOR . 'banco.sqlite';
    }

    protected function tearDown(): void
    {
        if (is_file($this->databasePath)) {
            unlink($this->databasePath);
        }

        $directory = dirname($this->databasePath);

        if (is_dir($directory)) {
            rmdir($directory);
        }
    }

    public function testCriaODiretorioEOArquivoDoBancoQuandoInexistentes(): void
    {
        self::assertDirectoryDoesNotExist(dirname($this->databasePath));

        $connection = new Connection($this->databasePath);

        self::assertFileExists($this->databasePath);
        self::assertInstanceOf(PDO::class, $connection->pdo());
    }

    public function testHabilitaChavesEstrangeiras(): void
    {
        $connection = new Connection($this->databasePath);

        $statement = $connection->pdo()->query('PRAGMA foreign_keys');

        self::assertSame('1', (string) $statement->fetchColumn());
    }

    public function testSuportaBancoEmMemoria(): void
    {
        $connection = new Connection(':memory:');

        $connection->pdo()->exec('CREATE TABLE teste (id TEXT PRIMARY KEY)');

        self::assertNotFalse($connection->pdo()->query('SELECT * FROM teste'));
    }
}
