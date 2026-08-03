<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Presentation\Web;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Controllers\InscritosListaDeEsperaController;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Tests\Support\HttpClientStub;

final class InscritosListaDeEsperaControllerTest extends TestCase
{
    public function testEstadoVazioPorPadrao(): void
    {
        $controller = new InscritosListaDeEsperaController(new HttpClientStub());

        $resposta = $controller->index(Request::criar('GET', '/lista-espera'));

        $this->assertSame(200, $resposta->status);
        $this->assertStringContainsString('Nenhuma inscrição na lista de espera até o momento.', $resposta->corpo);
    }

    public function testListaInscritosQuandoHaDados(): void
    {
        $controller = new InscritosListaDeEsperaController(new HttpClientStub([
            'waitlist' => [[
                'id' => 'insc-1',
                'email' => 'interessado@psyche.ai',
                'nome' => 'Ana Interessada',
                'profissao' => 'Psicóloga',
                'instituicao' => 'Universidade Federal',
                'paisEstado' => 'Brasil/SP',
                'motivoInteresse' => 'Quero participar da pesquisa.',
                'aceitouPoliticaPrivacidade' => true,
                'aceitouTermoConsentimento' => true,
                'criadoEm' => '2026-08-01T10:00:00+00:00',
            ]],
        ]));

        $resposta = $controller->index(Request::criar('GET', '/lista-espera'));

        $this->assertStringContainsString('Ana Interessada', $resposta->corpo);
        $this->assertStringContainsString('interessado@psyche.ai', $resposta->corpo);
        $this->assertStringContainsString('Quero participar da pesquisa.', $resposta->corpo);
    }
}
