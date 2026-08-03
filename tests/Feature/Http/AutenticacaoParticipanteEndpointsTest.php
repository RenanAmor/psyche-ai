<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

/**
 * Sistema de autenticação da ECO, independente do Analista: sem endpoint
 * de cadastro exposto pela API (provisionamento só via
 * bin/criar-participante.php) — por isso os testes semeiam a conta
 * diretamente via `$this->provider->participantes()`, que HttpTestCase já
 * expõe.
 */
final class AutenticacaoParticipanteEndpointsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->provider->participantes()->criar('participante@psyche.ai', 'segredo');
    }

    public function testPostComCredenciaisCorretasRetorna200ComEnvelopeDeSucesso(): void
    {
        $response = $this->despachar('POST', '/auth/participante/login', ['email' => 'participante@psyche.ai', 'senha' => 'segredo']);

        self::assertSame(200, $response->status());

        $corpo = $this->decodificar($response);

        self::assertTrue($corpo['success']);
        self::assertSame('participante@psyche.ai', $corpo['data']['email']);
        self::assertArrayNotHasKey('senha', $corpo['data']);
        self::assertArrayNotHasKey('senhaHash', $corpo['data']);
    }

    public function testPostComSenhaIncorretaRetorna401(): void
    {
        $response = $this->despachar('POST', '/auth/participante/login', ['email' => 'participante@psyche.ai', 'senha' => 'errada']);

        self::assertSame(401, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }

    public function testPostComEmailInexistenteRetorna401(): void
    {
        $response = $this->despachar('POST', '/auth/participante/login', ['email' => 'inexistente@psyche.ai', 'senha' => 'segredo']);

        self::assertSame(401, $response->status());
    }

    public function testPostComCampoObrigatorioAusenteRetorna400(): void
    {
        $response = $this->despachar('POST', '/auth/participante/login', ['email' => 'participante@psyche.ai']);

        self::assertSame(400, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }
}
