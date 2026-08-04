<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

/**
 * Videochamada Embutida: EventoDiscursivo ganha um campo real de autoria.
 * O backfill por paridade de posicao (par=sujeito, ímpar=sistema) só é
 * seguro neste ponto no tempo porque toda linha pré-existente veio do
 * fluxo de chat da ECO (alternância estrita Sujeito/Sistema, Sprint 12) ou
 * de transcrição de áudio do Sujeito (Sprint 22) — nenhuma sessão de
 * vídeo com dois locutores humanos existia antes desta migration. Não é
 * válido reaplicar essa lógica para qualquer inserção futura.
 */
final class AddLocutorToEventosDiscursivosTable implements Migration
{
    public function version(): string
    {
        return '0013';
    }

    public function description(): string
    {
        return 'Adiciona a coluna locutor em eventos_discursivos, com backfill por paridade de posicao';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            "ALTER TABLE eventos_discursivos ADD COLUMN locutor TEXT NOT NULL DEFAULT 'desconhecido'"
        );

        $pdo->exec(
            "UPDATE eventos_discursivos
             SET locutor = CASE WHEN posicao % 2 = 0 THEN 'sujeito' ELSE 'sistema' END"
        );
    }
}
