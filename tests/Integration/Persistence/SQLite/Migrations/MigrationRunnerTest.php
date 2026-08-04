<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite\Migrations;

use PsycheAI\Infrastructure\Persistence\SQLite\Connection;
use PsycheAI\Infrastructure\Persistence\SQLite\Migrations\AddLocutorToEventosDiscursivosTable;
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

    public function testBackfillDeLocutorPorParidadeDePosicao(): void
    {
        $pdo = (new Connection(':memory:'))->pdo();

        // Aplica todas as migrations padrão exceto AddLocutorToEventosDiscursivosTable
        // (aplicada manualmente abaixo), reproduzindo o schema exatamente como
        // estava antes dela existir.
        $migrationsAnteriores = array_filter(
            MigrationRunner::migrationsPadrao(),
            static fn ($migration): bool => !($migration instanceof AddLocutorToEventosDiscursivosTable)
        );
        (new MigrationRunner($pdo, $migrationsAnteriores))->run();

        $pdo->exec("INSERT INTO sujeitos (id, nome) VALUES ('sujeito-1', 'Sujeito Um')");
        $pdo->exec("INSERT INTO sessoes (id, sujeito_id, data) VALUES ('sessao-1', 'sujeito-1', '2026-01-10 10:00:00')");
        $pdo->exec("INSERT INTO discursos (id, sessao_id, conteudo) VALUES ('discurso-1', 'sessao-1', 'Conversa')");
        $pdo->exec(
            "INSERT INTO eventos_discursivos (id, discurso_id, conteudo, posicao)
             VALUES ('e0', 'discurso-1', 'fala do sujeito', 0), ('e1', 'discurso-1', 'resposta do sistema', 1)"
        );

        (new AddLocutorToEventosDiscursivosTable())->up($pdo);

        $statement = $pdo->query('SELECT id, locutor FROM eventos_discursivos ORDER BY posicao ASC');
        $linhas = $statement->fetchAll(\PDO::FETCH_KEY_PAIR);

        self::assertSame('sujeito', $linhas['e0']);
        self::assertSame('sistema', $linhas['e1']);
    }
}
