<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Migrations;

use PDO;

/**
 * Captura de interesse na ECO para quem esbarra no login do Participante
 * sem conta — tabela própria, independente de analistas/participantes.
 * `aceitou_politica_privacidade`/`aceitou_termo_consentimento` (0/1) são
 * NOT NULL: a Application Service nunca insere uma linha com qualquer um
 * dos dois em falso, mas a coluna continua explícita no schema porque a
 * tela de inscritos do Analista precisa exibir o valor real.
 */
final class CreateListaDeEsperaTable implements Migration
{
    public function version(): string
    {
        return '0012';
    }

    public function description(): string
    {
        return 'Cria a tabela lista_espera';
    }

    public function up(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS lista_espera (
                id TEXT PRIMARY KEY,
                email TEXT NOT NULL UNIQUE,
                nome TEXT NOT NULL,
                profissao TEXT,
                instituicao TEXT,
                pais_estado TEXT NOT NULL,
                motivo_interesse TEXT NOT NULL,
                aceitou_politica_privacidade INTEGER NOT NULL,
                aceitou_termo_consentimento INTEGER NOT NULL,
                criado_em TEXT NOT NULL
            )'
        );
    }
}
