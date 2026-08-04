<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

/**
 * Anotações do analista com autosave: uma linha por Sessao (sessao_id
 * UNIQUE), upsert no mapper em vez de buscar-depois-salvar a cada
 * debounce.
 */
final class CreateAnotacoesSessaoTable implements Migration
{
    public function version(): string
    {
        return '0015';
    }

    public function description(): string
    {
        return 'Cria a tabela anotacoes_sessao';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS anotacoes_sessao (
                id TEXT PRIMARY KEY,
                sessao_id TEXT NOT NULL UNIQUE REFERENCES sessoes(id) ON DELETE CASCADE,
                conteudo TEXT NOT NULL DEFAULT '',
                atualizado_em TEXT NOT NULL
            )"
        );
    }
}
