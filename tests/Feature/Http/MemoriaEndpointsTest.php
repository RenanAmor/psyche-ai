<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

final class MemoriaEndpointsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->provider->sujeitos()->criar('sujeito-1', 'Sujeito Um');
        $this->provider->sessoes()->criar('sujeito-1', 'sessao-1', new \DateTimeImmutable('2026-01-10 10:00:00'));
        $this->provider->sessoes()->criar('sujeito-1', 'sessao-2', new \DateTimeImmutable('2026-01-20 10:00:00'));
    }

    public function testPostCriaUmaMemoriaComAsSessoesDoSujeito(): void
    {
        $response = $this->despachar('POST', '/memories', ['sujeitoId' => 'sujeito-1', 'id' => 'memoria-1']);

        $corpo = $this->decodificar($response);

        self::assertSame(201, $response->status());
        self::assertSame('memoria-1', $corpo['data']['id']);
        self::assertSame(2, $corpo['data']['quantidadeDeSessoes']);
        self::assertCount(2, $corpo['data']['sessoes']);
    }

    public function testPostComSujeitoInexistenteRetorna404(): void
    {
        $response = $this->despachar('POST', '/memories', ['sujeitoId' => 'sujeito-inexistente', 'id' => 'memoria-1']);

        self::assertSame(404, $response->status());
    }

    public function testPostComIdJaExistenteRetorna409(): void
    {
        $this->despachar('POST', '/memories', ['sujeitoId' => 'sujeito-1', 'id' => 'memoria-1']);

        $response = $this->despachar('POST', '/memories', ['sujeitoId' => 'sujeito-1', 'id' => 'memoria-1']);

        self::assertSame(409, $response->status());
    }

    public function testGetListaTodasAsMemorias(): void
    {
        $this->despachar('POST', '/memories', ['sujeitoId' => 'sujeito-1', 'id' => 'memoria-1']);

        $response = $this->despachar('GET', '/memories');

        self::assertSame(200, $response->status());
        self::assertCount(1, $this->decodificar($response)['data']);
    }

    public function testGetPorIdInexistenteRetorna404(): void
    {
        $response = $this->despachar('GET', '/memories/inexistente');

        self::assertSame(404, $response->status());
    }

    public function testPutReconstroiOPivoDeSessoes(): void
    {
        $this->despachar('POST', '/memories', ['sujeitoId' => 'sujeito-1', 'id' => 'memoria-1']);

        $response = $this->despachar('PUT', '/memories/memoria-1', ['sujeitoId' => 'sujeito-1']);

        self::assertSame(200, $response->status());
        self::assertSame(2, $this->decodificar($response)['data']['quantidadeDeSessoes']);
    }

    public function testPutEmMemoriaInexistenteRetorna404(): void
    {
        $response = $this->despachar('PUT', '/memories/inexistente', ['sujeitoId' => 'sujeito-1']);

        self::assertSame(404, $response->status());
    }

    public function testDeleteRemoveAMemoriaERetorna204(): void
    {
        $this->despachar('POST', '/memories', ['sujeitoId' => 'sujeito-1', 'id' => 'memoria-1']);

        $response = $this->despachar('DELETE', '/memories/memoria-1');

        self::assertSame(204, $response->status());
        self::assertSame(404, $this->despachar('GET', '/memories/memoria-1')->status());
    }

    public function testDeleteEmMemoriaInexistenteRetorna404(): void
    {
        $response = $this->despachar('DELETE', '/memories/inexistente');

        self::assertSame(404, $response->status());
    }
}
