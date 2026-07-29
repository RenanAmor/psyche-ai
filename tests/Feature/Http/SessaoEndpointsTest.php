<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

final class SessaoEndpointsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->provider->sujeitos()->criar('sujeito-1', 'Sujeito Um');
    }

    public function testPostCriaUmaSessaoERetorna201ComEnvelopeDeSucesso(): void
    {
        $response = $this->despachar('POST', '/sessions', [
            'sujeitoId' => 'sujeito-1',
            'id' => 'sessao-1',
            'data' => '2026-01-10 10:00:00',
        ]);

        self::assertSame(201, $response->status());

        $corpo = $this->decodificar($response);

        self::assertTrue($corpo['success']);
        self::assertSame('sessao-1', $corpo['data']['id']);
        self::assertSame('2026-01-10 10:00:00', $corpo['data']['data']);
        self::assertSame(0, $corpo['data']['quantidadeDeDiscursos']);
    }

    public function testPostComCampoObrigatorioAusenteRetorna400(): void
    {
        $response = $this->despachar('POST', '/sessions', [
            'sujeitoId' => 'sujeito-1',
            'id' => 'sessao-1',
        ]);

        $corpo = $this->decodificar($response);

        self::assertSame(400, $response->status());
        self::assertFalse($corpo['success']);
        self::assertArrayHasKey('message', $corpo);
    }

    public function testPostComSujeitoInexistenteRetorna404(): void
    {
        $response = $this->despachar('POST', '/sessions', [
            'sujeitoId' => 'sujeito-inexistente',
            'id' => 'sessao-1',
            'data' => '2026-01-10 10:00:00',
        ]);

        self::assertSame(404, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }

    public function testPostComIdJaExistenteRetorna409(): void
    {
        $this->despachar('POST', '/sessions', [
            'sujeitoId' => 'sujeito-1',
            'id' => 'sessao-1',
            'data' => '2026-01-10 10:00:00',
        ]);

        $response = $this->despachar('POST', '/sessions', [
            'sujeitoId' => 'sujeito-1',
            'id' => 'sessao-1',
            'data' => '2026-01-20 10:00:00',
        ]);

        self::assertSame(409, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }

    public function testGetListaTodasAsSessoes(): void
    {
        $this->despachar('POST', '/sessions', ['sujeitoId' => 'sujeito-1', 'id' => 'sessao-1', 'data' => '2026-01-10 10:00:00']);
        $this->despachar('POST', '/sessions', ['sujeitoId' => 'sujeito-1', 'id' => 'sessao-2', 'data' => '2026-01-20 10:00:00']);

        $response = $this->despachar('GET', '/sessions');

        self::assertSame(200, $response->status());
        self::assertCount(2, $this->decodificar($response)['data']);
    }

    public function testGetPorIdRetornaASessao(): void
    {
        $this->despachar('POST', '/sessions', ['sujeitoId' => 'sujeito-1', 'id' => 'sessao-1', 'data' => '2026-01-10 10:00:00']);

        $response = $this->despachar('GET', '/sessions/sessao-1');

        self::assertSame(200, $response->status());
        self::assertSame('sessao-1', $this->decodificar($response)['data']['id']);
    }

    public function testGetPorIdInexistenteRetorna404(): void
    {
        $response = $this->despachar('GET', '/sessions/inexistente');

        self::assertSame(404, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }

    public function testPutAtualizaASessao(): void
    {
        $this->despachar('POST', '/sessions', ['sujeitoId' => 'sujeito-1', 'id' => 'sessao-1', 'data' => '2026-01-10 10:00:00']);

        $response = $this->despachar('PUT', '/sessions/sessao-1', ['data' => '2026-02-01 09:00:00']);

        self::assertSame(200, $response->status());
        self::assertSame('2026-02-01 09:00:00', $this->decodificar($response)['data']['data']);
    }

    public function testPutEmSessaoInexistenteRetorna404(): void
    {
        $response = $this->despachar('PUT', '/sessions/inexistente', ['data' => '2026-02-01 09:00:00']);

        self::assertSame(404, $response->status());
    }

    public function testDeleteRemoveASessaoERetorna204(): void
    {
        $this->despachar('POST', '/sessions', ['sujeitoId' => 'sujeito-1', 'id' => 'sessao-1', 'data' => '2026-01-10 10:00:00']);

        $response = $this->despachar('DELETE', '/sessions/sessao-1');

        self::assertSame(204, $response->status());
        self::assertSame('', $response->corpo());
        self::assertSame(404, $this->despachar('GET', '/sessions/sessao-1')->status());
    }

    public function testDeleteEmSessaoInexistenteRetorna404(): void
    {
        $response = $this->despachar('DELETE', '/sessions/inexistente');

        self::assertSame(404, $response->status());
    }
}
