<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PsycheAI\Infrastructure\Providers\ApplicationServiceProvider;

/**
 * Worker assíncrono da Videochamada Embutida (Daily.co): processa toda
 * ChamadaSessao encerrada cujas trilhas ainda não foram baixadas/transcritas
 * — mesmo padrão de bin/transcrever-gravacoes.php (polling, sem webhook).
 * Pensado para ser agendado periodicamente via cron do servidor (a cada
 * 2-5 minutos é suficiente).
 *
 * Uso: php bin/processar-chamadas-de-video.php
 */

$databasePath = getenv('PSYCHEAI_DATABASE_PATH') ?: __DIR__ . '/../storage/data/psyche-ai.sqlite';
$provider = ApplicationServiceProvider::comSQLite($databasePath);

$pendentes = $provider->chamadasDeVideo()->listarEncerradasNaoProcessadas();

if ($pendentes === []) {
    echo "Nenhuma chamada encerrada pendente de processamento.\n";

    exit(0);
}

$falhas = 0;

foreach ($pendentes as $chamada) {
    try {
        $provider->chamadasDeVideo()->processarGravacoes($chamada->sessaoId);

        echo sprintf("Chamada da sessão %s processada.\n", $chamada->sessaoId);
    } catch (Throwable $erro) {
        $falhas++;

        fwrite(STDERR, sprintf("Falha ao processar chamada da sessão %s: %s\n", $chamada->sessaoId, $erro->getMessage()));
    }
}

echo sprintf("%d chamada(s) processada(s), %d falha(s).\n", count($pendentes), $falhas);

exit($falhas > 0 ? 1 : 0);
