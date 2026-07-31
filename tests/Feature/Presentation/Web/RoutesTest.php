<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Router;
use PsycheAI\Presentation\Web\Navigation\NavigationMenu;
use PsycheAI\Presentation\Web\Routes;
use PsycheAI\Presentation\Web\Security\PortaoDeAnalista;
use PsycheAI\Tests\Support\HttpClientStub;
use PsycheAI\Tests\Support\ObservacoesHttpClientFake;

/**
 * Garante que a navegação é funcional de ponta a ponta: cada rota do
 * menu lateral resolve para uma página 200 através do Router real, e
 * uma rota inexistente cai no handler de "não encontrado" em vez de
 * lançar um erro fatal.
 *
 * Desde a revisão pós-Sprint 16, as rotas do menu ficam atrás de
 * `PortaoDeAnalista` — por isso setUp() autentica a "sessão" de testes
 * antes de exercitar a navegação. `testRotaProtegidaSemSessaoRedirecionaParaEntrar`
 * cobre o caso sem autenticação.
 */
final class RoutesTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        $_COOKIE = [];
        PortaoDeAnalista::abrirSessao(HttpClientStub::ANALISTA_ID_PADRAO);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        $_COOKIE = [];
    }

    private function criarRouter(): Router
    {
        $router = new Router();
        Routes::registrar($router, new HttpClientStub());

        return $router;
    }

    public function testTodaRotaDoMenuDeNavegacaoRespondeComSucesso(): void
    {
        $router = $this->criarRouter();

        foreach (NavigationMenu::itens() as $item) {
            $resposta = $router->despachar(Request::criar('GET', $item->rota));

            $this->assertSame(200, $resposta->status, sprintf('Rota "%s" deveria responder 200.', $item->rota));
        }
    }

    public function testRotaDeNovoSujeitoFuncional(): void
    {
        $resposta = $this->criarRouter()->despachar(Request::criar('GET', '/sujeitos/novo'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
    }

    public function testEnvioDoFormularioDeNovoSujeitoFuncional(): void
    {
        $resposta = $this->criarRouter()->despachar(
            Request::criar('POST', '/sujeitos', [], ['id' => 'sub-999', 'nome' => 'Nova Pessoa'])
        );

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<table', $resposta->corpo);
    }

    public function testFluxoCompletoDeCrudDeSujeitoFuncionalAtravesDasRotas(): void
    {
        $router = $this->criarRouter();

        $mostrar = $router->despachar(Request::criar('GET', '/sujeitos/sub-001'));
        $this->assertSame(200, $mostrar->status);
        $this->assertStringContainsString('Sujeito A', $mostrar->corpo);

        $editar = $router->despachar(Request::criar('GET', '/sujeitos/sub-001/editar'));
        $this->assertSame(200, $editar->status);
        $this->assertStringContainsString('<form', $editar->corpo);

        $atualizar = $router->despachar(Request::criar('POST', '/sujeitos/sub-001', [], ['nome' => 'Sujeito Atualizado']));
        $this->assertSame(200, $atualizar->status);

        $excluir = $router->despachar(Request::criar('POST', '/sujeitos/sub-001/excluir'));
        $this->assertSame(200, $excluir->status);

        $naoEncontrado = $router->despachar(Request::criar('GET', '/sujeitos/sub-001'));
        $this->assertSame(404, $naoEncontrado->status);
    }

    public function testRotasDeErroSimuladoFuncionais(): void
    {
        $router = $this->criarRouter();

        $this->assertSame(502, $router->despachar(Request::criar('GET', '/erros/comunicacao'))->status);
        $this->assertSame(422, $router->despachar(Request::criar('GET', '/erros/validacao'))->status);
        $this->assertSame(500, $router->despachar(Request::criar('GET', '/erros/interno'))->status);
        $this->assertSame(504, $router->despachar(Request::criar('GET', '/erros/timeout'))->status);
    }

    public function testRotaDeHistoricoDoSujeitoFuncional(): void
    {
        $resposta = $this->criarRouter()->despachar(Request::criar('GET', '/sujeitos/sub-001/historico'));

        $this->assertContains($resposta->status, [200, 404]);
    }

    public function testRotaInexistenteUsaHandlerDeNaoEncontrado(): void
    {
        $resposta = $this->criarRouter()->despachar(Request::criar('GET', '/rota-inexistente'));

        $this->assertSame(404, $resposta->status);
        $this->assertStringContainsString('Recurso não encontrado', $resposta->corpo);
    }

    public function testRotaProtegidaSemSessaoRedirecionaParaEntrar(): void
    {
        $_SESSION = [];

        $resposta = $this->criarRouter()->despachar(Request::criar('GET', '/'));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/entrar', $resposta->headers['Location']);
    }

    public function testRotaDoGrafoCircuitoProtegidaSemSessaoRedirecionaParaEntrar(): void
    {
        $_SESSION = [];

        $resposta = $this->criarRouter()->despachar(Request::criar('GET', '/sujeitos/sub-001/observacoes/grafo-circuito'));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/entrar', $resposta->headers['Location']);
    }

    public function testRotaDoGrafoCircuitoFuncionalRetornaJson(): void
    {
        $router = new Router();
        Routes::registrar($router, new ObservacoesHttpClientFake(
            recursos: ['subjects' => [['id' => 'sub-001', 'nome' => 'Sujeito A', 'quantidadeDeSessoes' => 2]]],
            circuitos: [[
                'id' => '1',
                'descricao' => 'lapso',
                'frequencia' => 2,
                'ocorrencias' => [
                    ['sessaoId' => 'sessao-1', 'discursoId' => 'd1', 'eventoId' => 'e1', 'momento' => '2026-01-10 10:00:00', 'posicao' => 0],
                    ['sessaoId' => 'sessao-2', 'discursoId' => 'd2', 'eventoId' => 'e2', 'momento' => '2026-01-20 10:00:00', 'posicao' => 0],
                ],
            ]]
        ));

        $resposta = $router->despachar(Request::criar('GET', '/sujeitos/sub-001/observacoes/grafo-circuito'));

        $this->assertSame(200, $resposta->status);

        $dados = json_decode($resposta->corpo, true);

        $this->assertTrue($dados['sucesso']);
        $this->assertCount(2, $dados['nos']);
    }

    public function testRotaDeEntrarNaoEProtegida(): void
    {
        $_SESSION = [];

        $resposta = $this->criarRouter()->despachar(Request::criar('GET', '/entrar'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
    }

    public function testFluxoDeAutenticacaoDoAnalistaFuncionalAtravesDasRotas(): void
    {
        $_SESSION = [];
        $router = $this->criarRouter();

        $comSenhaErrada = $router->despachar(
            Request::criar('POST', '/entrar', [], [
                'email' => HttpClientStub::ANALISTA_EMAIL_PADRAO,
                'senha' => 'errada',
            ])
        );
        $this->assertSame(422, $comSenhaErrada->status);
        $this->assertFalse(PortaoDeAnalista::estaAutenticado());

        $comSenhaCerta = $router->despachar(
            Request::criar('POST', '/entrar', [], [
                'email' => HttpClientStub::ANALISTA_EMAIL_PADRAO,
                'senha' => HttpClientStub::ANALISTA_SENHA_PADRAO,
            ])
        );
        $this->assertSame(302, $comSenhaCerta->status);
        $this->assertSame('/', $comSenhaCerta->headers['Location']);
        $this->assertTrue(PortaoDeAnalista::estaAutenticado());

        $sair = $router->despachar(Request::criar('POST', '/sair'));
        $this->assertSame(302, $sair->status);
        $this->assertSame('/entrar', $sair->headers['Location']);
        $this->assertFalse(PortaoDeAnalista::estaAutenticado());
    }

    public function testRotaDeCadastroDoSujeitoNaoEProtegida(): void
    {
        $_SESSION = [];

        $resposta = $this->criarRouter()->despachar(Request::criar('GET', '/conversa/cadastro'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
    }

    public function testRotaDeEntrarDoSujeitoNaoEProtegida(): void
    {
        $_SESSION = [];

        $resposta = $this->criarRouter()->despachar(Request::criar('GET', '/conversa/entrar'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('<form', $resposta->corpo);
    }

    public function testFluxoDeAutenticacaoDoSujeitoFuncionalAtravesDasRotas(): void
    {
        $_SESSION = [];
        $router = $this->criarRouter();

        $comSenhaErrada = $router->despachar(
            Request::criar('POST', '/conversa/entrar', [], [
                'email' => HttpClientStub::SUJEITO_EMAIL_PADRAO,
                'senha' => 'errada',
            ])
        );
        $this->assertSame(422, $comSenhaErrada->status);

        $comSenhaCerta = $router->despachar(
            Request::criar('POST', '/conversa/entrar', [], [
                'email' => HttpClientStub::SUJEITO_EMAIL_PADRAO,
                'senha' => HttpClientStub::SUJEITO_SENHA_PADRAO,
            ])
        );
        $this->assertSame(302, $comSenhaCerta->status);
        $this->assertSame('/conversa', $comSenhaCerta->headers['Location']);
        $this->assertSame(HttpClientStub::SUJEITO_ID_PADRAO, $_COOKIE['psyche_pessoa_id']);

        $sair = $router->despachar(Request::criar('POST', '/conversa/sair'));
        $this->assertSame(302, $sair->status);
        $this->assertSame('/conversa', $sair->headers['Location']);
        $this->assertArrayNotHasKey('psyche_pessoa_id', $_COOKIE);
    }
}
