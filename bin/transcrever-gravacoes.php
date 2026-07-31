<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PsycheAI\Application\Exceptions\TranscricaoFalhouException;
use PsycheAI\Infrastructure\Providers\ApplicationServiceProvider;

/**
 * Worker assíncrono da captura de áudio (Sprint 22): processa toda
 * GravacaoAudio pendente, chamando OpenAIWhisperTranscriptionService e
 * dividindo o resultado nos mesmos EventoDiscursivo que uma mensagem
 * digitada produz. Roda sobre o mesmo banco que public/index.php usa —
 * nenhuma rota HTTP dispara transcrição diretamente (mesmo padrão de
 * bin/criar-analista.php, que também é invocado fora do ciclo de
 * requisição). Pensado para ser agendado periodicamente via cron do
 * servidor (fora do escopo deste script agendar a si mesmo).
 *
 * Uso: php bin/transcrever-gravacoes.php
 */

$databasePath = getenv('PSYCHEAI_DATABASE_PATH') ?: __DIR__ . '/../storage/data/psyche-ai.sqlite';
$provider = ApplicationServiceProvider::comSQLite($databasePath);

$pendentes = $provider->gravacoesAudio()->listarPendentes();

if ($pendentes === []) {
    echo "Nenhuma gravação pendente de transcrição.\n";

    exit(0);
}

$falhas = 0;

foreach ($pendentes as $gravacao) {
    try {
        $provider->gravacoesAudio()->transcrever($gravacao->id);

        echo sprintf("Gravação %s transcrita.\n", $gravacao->id);
    } catch (TranscricaoFalhouException $erro) {
        $falhas++;

        fwrite(STDERR, $erro->getMessage() . "\n");
    }
}

echo sprintf("%d gravação(ões) processada(s), %d falha(s).\n", count($pendentes), $falhas);

exit($falhas > 0 ? 1 : 0);
