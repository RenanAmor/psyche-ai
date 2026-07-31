<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Infrastructure\Providers\ApplicationServiceProvider;

/**
 * Provisiona a primeira conta de analista (Sprint 18, "Plataforma") —
 * substitui a antiga PSYCHEAI_SENHA_ANALISTA única. Sem tela de cadastro
 * público: o único uso real hoje é o próprio dono do sistema, então um
 * comando de terminal roda direto sobre o mesmo banco que
 * public/index.php usa, sem expor nenhuma rota HTTP para isso.
 *
 * Uso: php bin/criar-analista.php email@exemplo.com "senha secreta"
 */

[, $email, $senha] = array_pad($argv, 3, null);

if ($email === null || $senha === null) {
    fwrite(STDERR, "Uso: php bin/criar-analista.php <email> <senha>\n");

    exit(1);
}

$databasePath = getenv('PSYCHEAI_DATABASE_PATH') ?: __DIR__ . '/../storage/data/psyche-ai.sqlite';
$provider = ApplicationServiceProvider::comSQLite($databasePath);

if ($provider->analistas()->buscarPorEmail($email) !== null) {
    fwrite(STDERR, sprintf("Já existe um analista com o e-mail \"%s\".\n", $email));

    exit(1);
}

try {
    $analista = $provider->analistas()->criar($email, $senha);
} catch (ComandoInvalidoException $erro) {
    fwrite(STDERR, $erro->getMessage() . "\n");

    exit(1);
}

echo sprintf("Analista criado: %s (%s)\n", $analista->email, $analista->id);
