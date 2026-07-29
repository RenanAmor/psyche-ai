<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

final class CreateMemoriasLongitudinaisTable implements Migration
{
    public function version(): string
    {
        return '0005';
    }

    public function description(): string
    {
        return 'Cria a tabela memorias_longitudinais';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS memorias_longitudinais (
                id TEXT PRIMARY KEY
            )'
        );
    }
}
