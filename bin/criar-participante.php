<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Infrastructure\Providers\ApplicationServiceProvider;

/**
 * Provisiona uma conta de participante da ECO — sistema de autenticação
 * independente do Analista (`bin/criar-analista.php`), assim como
 * `PortaoDeParticipante` é independente de `PortaoDeAnalista`. Sem tela de
 * cadastro público: contas da ECO são criadas pelo Analista, um comando de
 * terminal roda direto sobre o mesmo banco que public/index.php usa, sem
 * expor nenhuma rota HTTP para isso — mesma filosofia de
 * bin/criar-analista.php.
 *
 * Uso: php bin/criar-participante.php email@exemplo.com "senha secreta"
 */

[, $email, $senha] = array_pad($argv, 3, null);

if ($email === null || $senha === null) {
    fwrite(STDERR, "Uso: php bin/criar-participante.php <email> <senha>\n");

    exit(1);
}

$databasePath = getenv('PSYCHEAI_DATABASE_PATH') ?: __DIR__ . '/../storage/data/psyche-ai.sqlite';
$provider = ApplicationServiceProvider::comSQLite($databasePath);

if ($provider->participantes()->buscarPorEmail($email) !== null) {
    fwrite(STDERR, sprintf("Já existe um participante com o e-mail \"%s\".\n", $email));

    exit(1);
}

try {
    $participante = $provider->participantes()->criar($email, $senha);
} catch (ComandoInvalidoException $erro) {
    fwrite(STDERR, $erro->getMessage() . "\n");

    exit(1);
}

echo sprintf("Participante criado: %s (%s)\n", $participante->email, $participante->id);
