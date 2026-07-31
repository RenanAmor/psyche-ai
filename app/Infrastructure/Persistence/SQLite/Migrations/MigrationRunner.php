<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use DateTimeImmutable;
use PDO;
use Throwable;

/**
 * Aplica, de forma idempotente e ordenada, as migrations pendentes,
 * registrando o histórico na tabela schema_migrations.
 */
final class MigrationRunner
{
    /**
     * @var Migration[]
     */
    private readonly array $migrations;

    /**
     * @param Migration[] $migrations
     */
    public function __construct(
        private readonly PDO $pdo,
        array $migrations
    ) {
        usort($migrations, static fn (Migration $a, Migration $b): int => $a->version() <=> $b->version());

        $this->migrations = $migrations;
    }

    /**
     * @return Migration[]
     */
    public static function migrationsPadrao(): array
    {
        return [
            new CreateSujeitosTable(),
            new CreateSessoesTable(),
            new CreateDiscursosTable(),
            new CreateEventosDiscursivosTable(),
            new CreateMemoriasLongitudinaisTable(),
            new CreateMemoriaSessoesTable(),
            new AddCriadoEmToEventosDiscursivosTable(),
            new CreateAnalistasTable(),
            new AddContaToSujeitosTable(),
        ];
    }

    public static function comMigrationsPadrao(PDO $pdo): self
    {
        return new self($pdo, self::migrationsPadrao());
    }

    public function run(): void
    {
        $this->assegurarTabelaDeControle();

        $aplicadas = $this->versoesAplicadas();

        foreach ($this->migrations as $migration) {
            if (in_array($migration->version(), $aplicadas, true)) {
                continue;
            }

            $this->aplicar($migration);
        }
    }

    /**
     * @return string[]
     */
    public function versoesAplicadas(): array
    {
        $this->assegurarTabelaDeControle();

        $statement = $this->pdo->query('SELECT version FROM schema_migrations');

        return array_column($statement->fetchAll(), 'version');
    }

    private function aplicar(Migration $migration): void
    {
        $transacaoIniciadaAqui = !$this->pdo->inTransaction();

        if ($transacaoIniciadaAqui) {
            $this->pdo->beginTransaction();
        }

        try {
            $migration->up($this->pdo);

            $statement = $this->pdo->prepare(
                'INSERT INTO schema_migrations (version, description, applied_at)
                 VALUES (:version, :description, :applied_at)'
            );
            $statement->execute([
                'version' => $migration->version(),
                'description' => $migration->description(),
                'applied_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]);

            if ($transacaoIniciadaAqui) {
                $this->pdo->commit();
            }
        } catch (Throwable $erro) {
            if ($transacaoIniciadaAqui) {
                $this->pdo->rollBack();
            }

            throw $erro;
        }
    }

    private function assegurarTabelaDeControle(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS schema_migrations (
                version TEXT PRIMARY KEY,
                description TEXT NOT NULL,
                applied_at TEXT NOT NULL
            )'
        );
    }
}
