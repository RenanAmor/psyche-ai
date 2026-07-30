<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Router;
use PsycheAI\Presentation\Web\Navigation\NavigationMenu;
use PsycheAI\Presentation\Web\Routes;
use PsycheAI\Tests\Support\HttpClientStub;

/**
 * Garante que a navegação é funcional de ponta a ponta: cada rota do
 * menu lateral resolve para uma página 200 através do Router real, e
 * uma rota inexistente cai no handler de "não encontrado" em vez de
 * lançar um erro fatal.
 */
final class RoutesTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
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
    }

    public function testRotaInexistenteUsaHandlerDeNaoEncontrado(): void
    {
        $resposta = $this->criarRouter()->despachar(Request::criar('GET', '/rota-inexistente'));

        $this->assertSame(404, $resposta->status);
        $this->assertStringContainsString('Recurso não encontrado', $resposta->corpo);
    }
}
