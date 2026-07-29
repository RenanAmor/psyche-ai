<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

final class CreateSessoesTable implements Migration
{
    public function version(): string
    {
        return '0002';
    }

    public function description(): string
    {
        return 'Cria a tabela sessoes';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS sessoes (
                id TEXT PRIMARY KEY,
                sujeito_id TEXT NULL REFERENCES sujeitos(id) ON DELETE CASCADE,
                data TEXT NOT NULL
            )'
        );

        $pdo->exec(
            'CREATE INDEX IF NOT EXISTS idx_sessoes_sujeito_id ON sessoes(sujeito_id)'
        );
    }
}
