<?php

declare(strict_types=1);

namespace PsycheAI\Presentation;

use PsycheAI\Infrastructure\Providers\ApplicationServiceProvider;
use PsycheAI\Presentation\Controllers\DiscursoController;
use PsycheAI\Presentation\Controllers\EventoDiscursivoController;
use PsycheAI\Presentation\Controllers\MemoriaController;
use PsycheAI\Presentation\Controllers\SessaoController;
use PsycheAI\Presentation\Http\Router;

/**
 * Registra todos os endpoints da Sprint 10 no Router, ligando cada rota
 * ao Controller correspondente montado a partir do
 * ApplicationServiceProvider. Único ponto que conhece simultaneamente
 * Router e Controllers — reaproveitado pelo front controller HTTP
 * (public/index.php) e pelos testes de Feature.
 */
final class Routes
{
    public static function registrar(Router $router, ApplicationServiceProvider $provider): void
    {
        $sessoes = new SessaoController($provider->sessoes());
        $discursos = new DiscursoController($provider->discursos());
        $eventos = new EventoDiscursivoController($provider->discursos());
        $memorias = new MemoriaController($provider->memorias(), $provider->sujeitoRepository());

        $router->post('/sessions', [$sessoes, 'criar']);
        $router->get('/sessions', [$sessoes, 'listar']);
        $router->get('/sessions/{id}', [$sessoes, 'buscar']);
        $router->put('/sessions/{id}', [$sessoes, 'atualizar']);
        $router->delete('/sessions/{id}', [$sessoes, 'excluir']);

        $router->post('/events', [$eventos, 'criar']);
        $router->get('/events', [$eventos, 'listar']);
        $router->get('/events/{id}', [$eventos, 'buscar']);
        $router->put('/events/{id}', [$eventos, 'atualizar']);
        $router->delete('/events/{id}', [$eventos, 'excluir']);

        $router->post('/memories', [$memorias, 'criar']);
        $router->get('/memories', [$memorias, 'listar']);
        $router->get('/memories/{id}', [$memorias, 'buscar']);
        $router->put('/memories/{id}', [$memorias, 'atualizar']);
        $router->delete('/memories/{id}', [$memorias, 'excluir']);

        $router->post('/discourses', [$discursos, 'criar']);
        $router->get('/discourses', [$discursos, 'listar']);
        $router->get('/discourses/{id}', [$discursos, 'buscar']);
        $router->put('/discourses/{id}', [$discursos, 'atualizar']);
        $router->delete('/discourses/{id}', [$discursos, 'excluir']);
    }
}
