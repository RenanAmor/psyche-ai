<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

/**
 * Sprint 22 (Captura de Áudio da Sessão): a gravação contínua e bruta de
 * uma Sessao falada, separada dos eventos_discursivos (texto já
 * transcrito) que ela origina.
 */
final class CreateGravacoesAudioTable implements Migration
{
    public function version(): string
    {
        return '0010';
    }

    public function description(): string
    {
        return 'Cria a tabela gravacoes_audio';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS gravacoes_audio (
                id TEXT PRIMARY KEY,
                sessao_id TEXT NOT NULL REFERENCES sessoes(id) ON DELETE CASCADE,
                caminho_armazenamento TEXT NOT NULL,
                status TEXT NOT NULL DEFAULT \'pendente\',
                criado_em TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                transcrita_em TEXT NULL
            )'
        );

        $pdo->exec(
            'CREATE INDEX IF NOT EXISTS idx_gravacoes_audio_sessao_id ON gravacoes_audio(sessao_id)'
        );

        $pdo->exec(
            'CREATE INDEX IF NOT EXISTS idx_gravacoes_audio_status ON gravacoes_audio(status)'
        );
    }
}
