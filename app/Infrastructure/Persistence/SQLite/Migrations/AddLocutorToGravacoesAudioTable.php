<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

/**
 * Videochamada Embutida: GravacaoAudio ganha um campo real de autoria.
 * Default 'sujeito' (não 'desconhecido') porque toda gravação
 * pré-existente veio de /conversa/audio (Sprint 22), que só captura o
 * microfone do Sujeito.
 */
final class AddLocutorToGravacoesAudioTable implements Migration
{
    public function version(): string
    {
        return '0014';
    }

    public function description(): string
    {
        return 'Adiciona a coluna locutor em gravacoes_audio, default sujeito';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            "ALTER TABLE gravacoes_audio ADD COLUMN locutor TEXT NOT NULL DEFAULT 'sujeito'"
        );
    }
}
