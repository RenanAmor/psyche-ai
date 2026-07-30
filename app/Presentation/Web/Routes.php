<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web;

use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Controllers\AbstractCrudResourceController;
use PsycheAI\Presentation\Web\Controllers\ConversaController;
use PsycheAI\Presentation\Web\Controllers\DashboardController;
use PsycheAI\Presentation\Web\Controllers\DiscursosController;
use PsycheAI\Presentation\Web\Controllers\ErrorController;
use PsycheAI\Presentation\Web\Controllers\EventosDiscursivosController;
use PsycheAI\Presentation\Web\Controllers\HistoricoSujeitoController;
use PsycheAI\Presentation\Web\Controllers\MemoriasController;
use PsycheAI\Presentation\Web\Controllers\SessoesController;
use PsycheAI\Presentation\Web\Controllers\SujeitosController;
use PsycheAI\Presentation\Web\Http\Router;

/**
 * Registra todas as rotas internas da interface web sobre um Router já
 * instanciado. Único ponto que conhece a lista completa de rotas —
 * NavigationMenu aponta para os mesmos caminhos, e RoutesTest garante
 * que nenhuma rota do menu fica sem handler.
 *
 * O Router da interface web só despacha GET/POST (formulários HTML não
 * enviam PUT/DELETE) — "atualizar" e "excluir" são POST em rotas próprias
 * (".../{id}" e ".../{id}/excluir"). Internamente, os Controllers usam o
 * HttpClientInterface injetado para falar PUT/DELETE de verdade com a API
 * REST. As rotas estáticas (".../novo", ".../{id}/editar") são registradas
 * antes das dinâmicas (".../{id}") para que "novo" não seja capturado
 * como id.
 */
final class Routes
{
    public static function registrar(Router $router, HttpClientInterface $httpClient): void
    {
        $dashboard = new DashboardController($httpClient);
        $sujeitos = new SujeitosController($httpClient);
        $sessoes = new SessoesController($httpClient);
        $discursos = new DiscursosController($httpClient);
        $memorias = new MemoriasController($httpClient);
        $eventosDiscursivos = new EventosDiscursivosController($httpClient);
        $conversa = new ConversaController($httpClient);
        $historico = new HistoricoSujeitoController($httpClient);
        $erros = new ErrorController();

        $router->get('/', $dashboard->index(...));

        self::registrarCrud($router, '/sujeitos', $sujeitos);
        $router->get('/sujeitos/{id}/historico', $historico->mostrar(...));
        self::registrarCrud($router, '/sessoes', $sessoes);
        self::registrarCrud($router, '/discursos', $discursos);
        self::registrarCrud($router, '/memorias', $memorias);

        $router->get('/eventos-discursivos', $eventosDiscursivos->index(...));

        $router->get('/conversa', $conversa->iniciar(...));
        $router->post('/conversa/enviar', $conversa->enviar(...));

        $router->get('/erros/comunicacao', $erros->comunicacao(...));
        $router->get('/erros/validacao', $erros->validacao(...));
        $router->get('/erros/interno', $erros->interno(...));
        $router->get('/erros/timeout', $erros->timeout(...));

        $router->naoEncontradoHandler($erros->naoEncontrado(...));
    }

    private static function registrarCrud(Router $router, string $prefixo, AbstractCrudResourceController $controller): void
    {
        $router->get($prefixo, $controller->index(...));
        $router->get($prefixo . '/novo', $controller->novo(...));
        $router->post($prefixo, $controller->store(...));
        $router->get($prefixo . '/{id}/editar', $controller->editar(...));
        $router->post($prefixo . '/{id}/excluir', $controller->excluir(...));
        $router->post($prefixo . '/{id}', $controller->atualizar(...));
        $router->get($prefixo . '/{id}', $controller->mostrar(...));
    }
}
