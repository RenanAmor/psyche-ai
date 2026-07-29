<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PsycheAI\Infrastructure\Providers\ApplicationServiceProvider;
use PsycheAI\Presentation\Http\ExceptionHandler;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Http\Router;
use PsycheAI\Presentation\Routes;

$databasePath = getenv('PSYCHEAI_DATABASE_PATH') ?: __DIR__ . '/../storage/data/psyche-ai.sqlite';

$provider = ApplicationServiceProvider::comSQLite($databasePath);

$router = new Router();
Routes::registrar($router, $provider);

$request = Request::capturarDoGlobals();

try {
    $response = $router->despachar($request);
} catch (Throwable $erro) {
    $response = ExceptionHandler::converter($erro);
}

$response->enviar();
