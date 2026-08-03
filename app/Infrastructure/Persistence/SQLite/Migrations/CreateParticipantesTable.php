<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

/**
 * Cria a tabela do sistema de autenticação da ECO — independente de
 * `analistas` (Portão do Analista) e de `sujeitos.email`/`senha_hash`
 * (Sprint 20, self-signup, retirada de uso em favor deste provisionamento
 * feito pelo Analista).
 */
final class CreateParticipantesTable implements Migration
{
    public function version(): string
    {
        return '0011';
    }

    public function description(): string
    {
        return 'Cria a tabela participantes';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS participantes (
                id TEXT PRIMARY KEY,
                email TEXT NOT NULL UNIQUE,
                senha_hash TEXT NOT NULL,
                criado_em TEXT NOT NULL
            )'
        );
    }
}
