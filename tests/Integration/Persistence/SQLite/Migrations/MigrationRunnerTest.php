<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite\Migrations;

use PsycheAI\Infrastructure\Persistence\SQLite\Migrations\MigrationRunner;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class MigrationRunnerTest extends SQLiteTestCase
{
    private const TABELAS_ESPERADAS = [
        'sujeitos',
        'sessoes',
        'discursos',
        'eventos_discursivos',
        'memorias_longitudinais',
        'memoria_sessoes',
        'schema_migrations',
    ];

    public function testCriaTodasAsTabelasDoEsquema(): void
    {
        $statement = $this->pdo->query("SELECT name FROM sqlite_master WHERE type = 'table'");
        $tabelas = $statement->fetchAll(\PDO::FETCH_COLUMN);

        foreach (self::TABELAS_ESPERADAS as $tabela) {
            self::assertContains($tabela, $tabelas);
        }
    }

    public function testRegistraAsVersoesAplicadas(): void
    {
        $runner = MigrationRunner::comMigrationsPadrao($this->pdo);

        $versoes = $runner->versoesAplicadas();

        self::assertSame(
            array_map(static fn ($migration) => $migration->version(), MigrationRunner::migrationsPadrao()),
            $versoes
        );
    }

    public function testExecutarNovamenteNaoDuplicaNemFalha(): void
    {
        $runner = MigrationRunner::comMigrationsPadrao($this->pdo);

        $runner->run();
        $runner->run();

        $statement = $this->pdo->query('SELECT COUNT(*) FROM schema_migrations');

        self::assertSame(count(MigrationRunner::migrationsPadrao()), (int) $statement->fetchColumn());
    }
}
