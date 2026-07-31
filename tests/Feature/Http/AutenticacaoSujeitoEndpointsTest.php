<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

final class AutenticacaoSujeitoEndpointsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->provider->sujeitos()->criar('sujeito-1', 'Visitante');
        $this->provider->sujeitos()->registrarConta('sujeito-1', 'sujeito@psyche.ai', 'segredo');
    }

    public function testPostComCredenciaisCorretasRetorna200ComOSujeito(): void
    {
        $response = $this->despachar('POST', '/auth/subject/login', ['email' => 'sujeito@psyche.ai', 'senha' => 'segredo']);

        self::assertSame(200, $response->status());

        $corpo = $this->decodificar($response);
        self::assertTrue($corpo['success']);
        self::assertSame('sujeito-1', $corpo['data']['id']);
        self::assertSame('sujeito@psyche.ai', $corpo['data']['email']);
    }

    public function testPostComSenhaIncorretaRetorna401(): void
    {
        $response = $this->despachar('POST', '/auth/subject/login', ['email' => 'sujeito@psyche.ai', 'senha' => 'errada']);

        self::assertSame(401, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }

    public function testPostComEmailInexistenteRetorna401(): void
    {
        $response = $this->despachar('POST', '/auth/subject/login', ['email' => 'inexistente@psyche.ai', 'senha' => 'segredo']);

        self::assertSame(401, $response->status());
    }

    public function testPostComCampoObrigatorioAusenteRetorna400(): void
    {
        $response = $this->despachar('POST', '/auth/subject/login', ['email' => 'sujeito@psyche.ai']);

        self::assertSame(400, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }
}
