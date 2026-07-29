<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

final class CreateEventosDiscursivosTable implements Migration
{
    public function version(): string
    {
        return '0004';
    }

    public function description(): string
    {
        return 'Cria a tabela eventos_discursivos';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS eventos_discursivos (
                id TEXT PRIMARY KEY,
                discurso_id TEXT NULL REFERENCES discursos(id) ON DELETE CASCADE,
                conteudo TEXT NOT NULL,
                posicao INTEGER NOT NULL
            )'
        );

        $pdo->exec(
            'CREATE INDEX IF NOT EXISTS idx_eventos_discursivos_discurso_id ON eventos_discursivos(discurso_id)'
        );
    }
}
