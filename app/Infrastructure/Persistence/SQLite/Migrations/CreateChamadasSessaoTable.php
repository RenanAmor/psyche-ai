<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

/**
 * Videochamada Embutida (Daily.co): sala + link mágico de acesso
 * vinculados a uma Sessao. `sessao_id UNIQUE` garante uma sala por
 * Sessao (idempotência ao clicar "Iniciar Videochamada" de novo antes de
 * expirar). `token_acesso UNIQUE` é o link mágico que o Sujeito usa para
 * entrar sem login/conta.
 */
final class CreateChamadasSessaoTable implements Migration
{
    public function version(): string
    {
        return '0016';
    }

    public function description(): string
    {
        return 'Cria a tabela chamadas_sessao';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS chamadas_sessao (
                id TEXT PRIMARY KEY,
                sessao_id TEXT NOT NULL UNIQUE REFERENCES sessoes(id) ON DELETE CASCADE,
                sala_provedor_id TEXT NOT NULL,
                sala_url TEXT NOT NULL,
                token_acesso TEXT NOT NULL UNIQUE,
                status TEXT NOT NULL DEFAULT 'criada',
                criada_em TEXT NOT NULL,
                expira_em TEXT NOT NULL,
                encerrada_em TEXT NULL,
                processada_em TEXT NULL
            )"
        );

        $pdo->exec(
            'CREATE INDEX IF NOT EXISTS idx_chamadas_sessao_token_acesso ON chamadas_sessao(token_acesso)'
        );
    }
}
