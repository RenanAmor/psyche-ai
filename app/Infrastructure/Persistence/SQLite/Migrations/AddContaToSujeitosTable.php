<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

/**
 * Sprint 20 ("Contas reais do Sujeito"): permite que um Sujeito, hoje
 * identificado só por um cookie pseudônimo (`psyche_pessoa_id`), ganhe
 * um login real sem perder seu histórico — email/senha_hash ficam nulas
 * até o Sujeito se cadastrar.
 */
final class AddContaToSujeitosTable implements Migration
{
    public function version(): string
    {
        return '0009';
    }

    public function description(): string
    {
        return 'Acrescenta email/senha_hash (nulos) à tabela sujeitos';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec('ALTER TABLE sujeitos ADD COLUMN email TEXT');
        $pdo->exec('ALTER TABLE sujeitos ADD COLUMN senha_hash TEXT');
        $pdo->exec('CREATE UNIQUE INDEX IF NOT EXISTS idx_sujeitos_email ON sujeitos(email) WHERE email IS NOT NULL');
    }
}
