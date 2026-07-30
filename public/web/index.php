<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use PsycheAI\Presentation\Web\Client\ApiHttpClient;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Router;
use PsycheAI\Presentation\Web\Routes;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$apiBaseUrl = getenv('PSYCHEAI_API_BASE_URL') ?: 'http://localhost:8000';

$method = (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET');
$path = (string) ($_SERVER['REQUEST_URI'] ?? '/');

$request = Request::criar($method, $path, $_GET, $_POST);

$router = new Router();
Routes::registrar($router, new ApiHttpClient($apiBaseUrl));

$router->despachar($request)->enviar();
