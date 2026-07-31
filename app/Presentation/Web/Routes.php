<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web;

use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Controllers\AbstractCrudResourceController;
use PsycheAI\Presentation\Web\Controllers\AutenticacaoAnalistaController;
use PsycheAI\Presentation\Web\Controllers\ConversaController;
use PsycheAI\Presentation\Web\Controllers\DashboardController;
use PsycheAI\Presentation\Web\Controllers\DiscursosController;
use PsycheAI\Presentation\Web\Controllers\ErrorController;
use PsycheAI\Presentation\Web\Controllers\EventosDiscursivosController;
use PsycheAI\Presentation\Web\Controllers\HistoricoSujeitoController;
use PsycheAI\Presentation\Web\Controllers\MemoriasController;
use PsycheAI\Presentation\Web\Controllers\ObservacoesSujeitoController;
use PsycheAI\Presentation\Web\Controllers\SessoesController;
use PsycheAI\Presentation\Web\Controllers\SujeitosController;
use PsycheAI\Presentation\Web\Http\Router;
use PsycheAI\Presentation\Web\Security\PortaoDeAnalista;

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
 *
 * Desde a revisão pós-Sprint 16 (docs/Roadmap.md), toda rota de
 * coleta/análise de dados (`/`, CRUDs, histórico, observações, eventos
 * discursivos) passa por `PortaoDeAnalista::proteger()`. `/conversa*`
 * (superfície do Sujeito) e `/entrar`/`/sair`/`/erros/*` permanecem
 * deliberadamente fora do Portão.
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
        $observacoes = new ObservacoesSujeitoController($httpClient);
        $erros = new ErrorController();
        $autenticacao = new AutenticacaoAnalistaController($httpClient);

        $router->get('/', PortaoDeAnalista::proteger($dashboard->index(...)));

        self::registrarCrud($router, '/sujeitos', $sujeitos);
        $router->get('/sujeitos/{id}/historico', PortaoDeAnalista::proteger($historico->mostrar(...)));
        $router->get('/sujeitos/{id}/observacoes', PortaoDeAnalista::proteger($observacoes->mostrar(...)));
        $router->get('/sujeitos/{id}/observacoes/grafo-circuito', PortaoDeAnalista::proteger($observacoes->grafoCircuito(...)));
        self::registrarCrud($router, '/sessoes', $sessoes);
        self::registrarCrud($router, '/discursos', $discursos);
        self::registrarCrud($router, '/memorias', $memorias);

        $router->get('/eventos-discursivos', PortaoDeAnalista::proteger($eventosDiscursivos->index(...)));

        $router->get('/conversa', $conversa->iniciar(...));
        $router->post('/conversa/enviar', $conversa->enviar(...));
        $router->post('/conversa/mensagens', $conversa->mensagens(...));

        $router->get('/entrar', $autenticacao->entrar(...));
        $router->post('/entrar', $autenticacao->autenticar(...));
        $router->post('/sair', $autenticacao->sair(...));

        $router->get('/erros/comunicacao', $erros->comunicacao(...));
        $router->get('/erros/validacao', $erros->validacao(...));
        $router->get('/erros/interno', $erros->interno(...));
        $router->get('/erros/timeout', $erros->timeout(...));

        $router->naoEncontradoHandler($erros->naoEncontrado(...));
    }

    private static function registrarCrud(Router $router, string $prefixo, AbstractCrudResourceController $controller): void
    {
        $router->get($prefixo, PortaoDeAnalista::proteger($controller->index(...)));
        $router->get($prefixo . '/novo', PortaoDeAnalista::proteger($controller->novo(...)));
        $router->post($prefixo, PortaoDeAnalista::proteger($controller->store(...)));
        $router->get($prefixo . '/{id}/editar', PortaoDeAnalista::proteger($controller->editar(...)));
        $router->post($prefixo . '/{id}/excluir', PortaoDeAnalista::proteger($controller->excluir(...)));
        $router->post($prefixo . '/{id}', PortaoDeAnalista::proteger($controller->atualizar(...)));
        $router->get($prefixo . '/{id}', PortaoDeAnalista::proteger($controller->mostrar(...)));
    }
}
