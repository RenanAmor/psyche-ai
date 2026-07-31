<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use PsycheAI\Presentation\Web\Client\ApiHttpClient;
use PsycheAI\Presentation\Web\Http\BasePath;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Router;
use PsycheAI\Presentation\Web\Routes;

// Sprint 21: PSYCHEAI_BASE_PATH (ex.: "/psycheai") permite rodar a
// interface web sob um subcaminho de um domínio compartilhado — o
// próprio Router/Routes.php continuam declarando rotas "limpas"
// (`/conversa`, `/sujeitos`, ...); o prefixo é removido aqui, na borda
// de entrada, e reaplicado na borda de saída (BasePath::url(), usado por
// Response::redirecionar/ButtonComponent/FormComponent/cookies/assets).
BasePath::definir((string) (getenv('PSYCHEAI_BASE_PATH') ?: ''));

$requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
$basePath = BasePath::valor();

if ($basePath !== '' && str_starts_with($requestUri, $basePath)) {
    $requestUri = substr($requestUri, strlen($basePath));
    $requestUri = $requestUri === '' ? '/' : $requestUri;
}

// Só relevante quando este arquivo é usado como router script do servidor
// embutido do PHP (`php -S ... public/web/index.php`, Sprint 19): esse modo
// executa o router para toda requisição, inclusive as de arquivo estático
// (ex.: `assets/js/grafo-circuito.js`), a menos que ele devolva `false` para
// deixar o servidor embutido servir o arquivo como está — mesmo
// comportamento que Apache/Nginx já dão de graça em produção, servindo
// arquivos reais de `public/web/` sem tocar em PHP.
if (PHP_SAPI === 'cli-server') {
    $caminhoRequisitado = __DIR__ . rawurldecode((string) parse_url($requestUri, PHP_URL_PATH));

    if ($caminhoRequisitado !== __FILE__ && is_file($caminhoRequisitado)) {
        // Com PSYCHEAI_BASE_PATH configurado (Sprint 21), `return false`
        // não funciona aqui: o servidor embutido procuraria o arquivo pelo
        // REQUEST_URI original (ainda com o prefixo), que não bate com o
        // caminho físico em disco. Servir direto evita depender desse
        // comportamento — só importa para teste local; em produção
        // (Apache/Nginx) o servidor real nunca passa por este arquivo para
        // pedir um asset estático.
        $tiposConhecidos = ['js' => 'text/javascript', 'css' => 'text/css', 'svg' => 'image/svg+xml'];
        $extensao = strtolower((string) pathinfo($caminhoRequisitado, PATHINFO_EXTENSION));

        header('Content-Type: ' . ($tiposConhecidos[$extensao] ?? 'application/octet-stream'));
        readfile($caminhoRequisitado);

        exit;
    }
}

if (session_status() === PHP_SESSION_NONE) {
    // Escopa o cookie de sessão nativo do PHP ao subcaminho configurado
    // — sem isso, sob /psycheai num domínio compartilhado, colidiria com
    // (ou vazaria para) a sessão do próprio investimentos369 na raiz.
    session_set_cookie_params(['path' => BasePath::url('/')]);
    session_start();
}

$apiBaseUrl = getenv('PSYCHEAI_API_BASE_URL') ?: 'http://localhost:8000';

$method = (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET');

$request = Request::criar($method, $requestUri, $_GET, $_POST);

$router = new Router();
Routes::registrar($router, new ApiHttpClient($apiBaseUrl));

$router->despachar($request)->enviar();
