<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

final class CreateMemoriaSessoesTable implements Migration
{
    public function version(): string
    {
        return '0006';
    }

    public function description(): string
    {
        return 'Cria a tabela de associação memoria_sessoes';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS memoria_sessoes (
                memoria_id TEXT NOT NULL REFERENCES memorias_longitudinais(id) ON DELETE CASCADE,
                sessao_id TEXT NOT NULL REFERENCES sessoes(id) ON DELETE CASCADE,
                ordem INTEGER NOT NULL,
                PRIMARY KEY (memoria_id, sessao_id)
            )'
        );
    }
}
