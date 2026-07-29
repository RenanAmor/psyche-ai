<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

final class CreateDiscursosTable implements Migration
{
    public function version(): string
    {
        return '0003';
    }

    public function description(): string
    {
        return 'Cria a tabela discursos';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS discursos (
                id TEXT PRIMARY KEY,
                sessao_id TEXT NULL REFERENCES sessoes(id) ON DELETE CASCADE,
                conteudo TEXT NOT NULL
            )'
        );

        $pdo->exec(
            'CREATE INDEX IF NOT EXISTS idx_discursos_sessao_id ON discursos(sessao_id)'
        );
    }
}
