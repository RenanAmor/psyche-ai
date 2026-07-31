<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

/**
 * Sprint 18 (Plataforma): substitui a senha única de
 * `PSYCHEAI_SENHA_ANALISTA` por contas reais de analista.
 */
final class CreateAnalistasTable implements Migration
{
    public function version(): string
    {
        return '0008';
    }

    public function description(): string
    {
        return 'Cria a tabela analistas';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS analistas (
                id TEXT PRIMARY KEY,
                email TEXT NOT NULL UNIQUE,
                senha_hash TEXT NOT NULL,
                criado_em TEXT NOT NULL
            )'
        );
    }
}
