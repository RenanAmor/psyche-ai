<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

final class AddCriadoEmToEventosDiscursivosTable implements Migration
{
    public function version(): string
    {
        return '0007';
    }

    public function description(): string
    {
        return 'Adiciona a coluna criado_em em eventos_discursivos';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            'ALTER TABLE eventos_discursivos ADD COLUMN criado_em TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP'
        );
    }
}
