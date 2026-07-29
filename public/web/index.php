<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Router;
use PsycheAI\Presentation\Web\Routes;

$method = (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET');
$path = (string) ($_SERVER['REQUEST_URI'] ?? '/');

$request = Request::criar($method, $path, $_GET, $_POST);

$router = new Router();
Routes::registrar($router);

$router->despachar($request)->enviar();
