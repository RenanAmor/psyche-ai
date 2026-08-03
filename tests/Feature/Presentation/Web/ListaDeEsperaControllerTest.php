<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\ListaDeEsperaController;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Tests\Support\HttpClientStub;

final class ListaDeEsperaControllerTest extends TestCase
{
    /**
     * @return array<string, string>
     */
    private function camposValidos(): array
    {
        return [
            'nome' => 'Ana Interessada',
            'email' => 'interessado@psyche.ai',
            'profissao' => 'Psicóloga',
            'instituicao' => 'Universidade Federal',
            'paisEstado' => 'Brasil/SP',
            'motivoInteresse' => 'Quero participar da pesquisa.',
            'aceitouPoliticaPrivacidade' => '1',
            'aceitouTermoConsentimento' => '1',
        ];
    }

    public function testInscreverComSucessoRedirecionaParaEntrarComFlagDeSucesso(): void
    {
        $controller = new ListaDeEsperaController(new HttpClientStub());

        $resposta = $controller->inscrever(Request::criar('POST', '/lista-espera', [], $this->camposValidos()));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/participante/entrar?inscrito=1', $resposta->headers['Location']);
    }

    public function testInscreverComFalhaRedirecionaParaEntrarComFlagDeErro(): void
    {
        $controller = new ListaDeEsperaController(HttpClientStub::comFalha(ErrorType::VALIDACAO));

        $resposta = $controller->inscrever(Request::criar('POST', '/lista-espera', [], ['email' => 'nao-e-email']));

        $this->assertSame(302, $resposta->status);
        $this->assertSame('/participante/entrar?erro_lista_espera=1', $resposta->headers['Location']);
    }
}
