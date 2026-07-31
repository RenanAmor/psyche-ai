<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use PsycheAI\Presentation\Web\Client\ApiHttpClient;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Router;
use PsycheAI\Presentation\Web\Routes;

// Só relevante quando este arquivo é usado como router script do servidor
// embutido do PHP (`php -S ... public/web/index.php`, Sprint 19): esse modo
// executa o router para toda requisição, inclusive as de arquivo estático
// (ex.: `assets/js/grafo-circuito.js`), a menos que ele devolva `false` para
// deixar o servidor embutido servir o arquivo como está — mesmo
// comportamento que Apache/Nginx já dão de graça em produção, servindo
// arquivos reais de `public/web/` sem tocar em PHP.
if (PHP_SAPI === 'cli-server') {
    $caminhoRequisitado = __DIR__ . rawurldecode((string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH));

    if ($caminhoRequisitado !== __FILE__ && is_file($caminhoRequisitado)) {
        return false;
    }
}

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
