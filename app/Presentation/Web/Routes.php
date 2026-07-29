<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web;

use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Client\MockApiHttpClient;
use PsycheAI\Presentation\Web\Controllers\DashboardController;
use PsycheAI\Presentation\Web\Controllers\DiscursosController;
use PsycheAI\Presentation\Web\Controllers\ErrorController;
use PsycheAI\Presentation\Web\Controllers\EventosDiscursivosController;
use PsycheAI\Presentation\Web\Controllers\MemoriasController;
use PsycheAI\Presentation\Web\Controllers\SessoesController;
use PsycheAI\Presentation\Web\Controllers\SujeitosController;
use PsycheAI\Presentation\Web\Http\Router;

/**
 * Registra todas as rotas internas da interface web sobre um Router já
 * instanciado. Único ponto que conhece a lista completa de rotas —
 * NavigationMenu aponta para os mesmos caminhos, e RoutesTest garante
 * que nenhuma rota do menu fica sem handler.
 */
final class Routes
{
    public static function registrar(Router $router, ?HttpClientInterface $httpClient = null): void
    {
        $httpClient ??= new MockApiHttpClient();

        $dashboard = new DashboardController($httpClient);
        $sujeitos = new SujeitosController($httpClient);
        $sessoes = new SessoesController($httpClient);
        $discursos = new DiscursosController($httpClient);
        $memorias = new MemoriasController($httpClient);
        $eventosDiscursivos = new EventosDiscursivosController($httpClient);
        $erros = new ErrorController();

        $router->get('/', $dashboard->index(...));

        $router->get('/sujeitos', $sujeitos->index(...));
        $router->get('/sujeitos/novo', $sujeitos->novo(...));
        $router->post('/sujeitos', $sujeitos->store(...));

        $router->get('/sessoes', $sessoes->index(...));
        $router->get('/discursos', $discursos->index(...));
        $router->get('/memorias', $memorias->index(...));
        $router->get('/eventos-discursivos', $eventosDiscursivos->index(...));

        $router->get('/erros/comunicacao', $erros->comunicacao(...));
        $router->get('/erros/validacao', $erros->validacao(...));
        $router->get('/erros/interno', $erros->interno(...));

        $router->naoEncontradoHandler($erros->naoEncontrado(...));
    }
}
