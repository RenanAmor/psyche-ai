<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

/**
 * Não há endpoint de cadastro de analista exposto pela API nesta passagem
 * (Sprint 18, escopo reduzido: provisionamento só via bin/criar-analista.php)
 * — por isso os testes semeiam a conta diretamente via
 * `$this->provider->analistas()`, que HttpTestCase já expõe.
 */
final class AutenticacaoEndpointsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->provider->analistas()->criar('analista@psyche.ai', 'segredo');
    }

    public function testPostComCredenciaisCorretasRetorna200ComEnvelopeDeSucesso(): void
    {
        $response = $this->despachar('POST', '/auth/login', ['email' => 'analista@psyche.ai', 'senha' => 'segredo']);

        self::assertSame(200, $response->status());

        $corpo = $this->decodificar($response);

        self::assertTrue($corpo['success']);
        self::assertSame('analista@psyche.ai', $corpo['data']['email']);
        self::assertArrayNotHasKey('senha', $corpo['data']);
        self::assertArrayNotHasKey('senhaHash', $corpo['data']);
    }

    public function testPostComSenhaIncorretaRetorna401(): void
    {
        $response = $this->despachar('POST', '/auth/login', ['email' => 'analista@psyche.ai', 'senha' => 'errada']);

        self::assertSame(401, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }

    public function testPostComEmailInexistenteRetorna401(): void
    {
        $response = $this->despachar('POST', '/auth/login', ['email' => 'inexistente@psyche.ai', 'senha' => 'segredo']);

        self::assertSame(401, $response->status());
    }

    public function testPostComCampoObrigatorioAusenteRetorna400(): void
    {
        $response = $this->despachar('POST', '/auth/login', ['email' => 'analista@psyche.ai']);

        self::assertSame(400, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }
}
