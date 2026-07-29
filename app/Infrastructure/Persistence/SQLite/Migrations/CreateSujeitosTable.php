<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

final class CreateSujeitosTable implements Migration
{
    public function version(): string
    {
        return '0001';
    }

    public function description(): string
    {
        return 'Cria a tabela sujeitos';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS sujeitos (
                id TEXT PRIMARY KEY,
                nome TEXT NOT NULL
            )'
        );
    }
}
