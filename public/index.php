<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PsycheAI\Infrastructure\Providers\ApplicationServiceProvider;
use PsycheAI\Presentation\Http\Cors;
use PsycheAI\Presentation\Http\ExceptionHandler;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Http\Response;
use PsycheAI\Presentation\Http\Router;
use PsycheAI\Presentation\Routes;

$databasePath = getenv('PSYCHEAI_DATABASE_PATH') ?: __DIR__ . '/../storage/data/psyche-ai.sqlite';

$provider = ApplicationServiceProvider::comSQLite($databasePath);

$router = new Router();
Routes::registrar($router, $provider);

$request = Request::capturarDoGlobals();

$cors = new Cors(array_filter(array_map('trim', explode(',', getenv('CORS_ALLOWED_ORIGINS') ?: ''))));
$cabecalhosCors = $cors->cabecalhosPara($_SERVER['HTTP_ORIGIN'] ?? null);

if ($cors->ehPreflight($request)) {
    (new Response(204, '', $cabecalhosCors))->enviar();
    exit;
}

try {
    $response = $router->despachar($request);
} catch (Throwable $erro) {
    $response = ExceptionHandler::converter($erro);
}

$response->comCabecalhosAdicionais($cabecalhosCors)->enviar();
