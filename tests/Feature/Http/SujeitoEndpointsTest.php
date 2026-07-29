<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

final class SujeitoEndpointsTest extends HttpTestCase
{
    public function testPostCriaUmSujeitoERetorna201ComEnvelopeDeSucesso(): void
    {
        $response = $this->despachar('POST', '/subjects', ['id' => 'sujeito-1', 'nome' => 'Sujeito Um']);

        self::assertSame(201, $response->status());

        $corpo = $this->decodificar($response);

        self::assertTrue($corpo['success']);
        self::assertSame('sujeito-1', $corpo['data']['id']);
        self::assertSame('Sujeito Um', $corpo['data']['nome']);
        self::assertSame(0, $corpo['data']['quantidadeDeSessoes']);
    }

    public function testPostComCampoObrigatorioAusenteRetorna400(): void
    {
        $response = $this->despachar('POST', '/subjects', ['id' => 'sujeito-1']);

        $corpo = $this->decodificar($response);

        self::assertSame(400, $response->status());
        self::assertFalse($corpo['success']);
        self::assertArrayHasKey('message', $corpo);
    }

    public function testPostComIdJaExistenteRetorna409(): void
    {
        $this->despachar('POST', '/subjects', ['id' => 'sujeito-1', 'nome' => 'Sujeito Um']);

        $response = $this->despachar('POST', '/subjects', ['id' => 'sujeito-1', 'nome' => 'Outro Nome']);

        self::assertSame(409, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }

    public function testGetListaTodosOsSujeitos(): void
    {
        $this->despachar('POST', '/subjects', ['id' => 'sujeito-1', 'nome' => 'Sujeito Um']);
        $this->despachar('POST', '/subjects', ['id' => 'sujeito-2', 'nome' => 'Sujeito Dois']);

        $response = $this->despachar('GET', '/subjects');

        self::assertSame(200, $response->status());
        self::assertCount(2, $this->decodificar($response)['data']);
    }

    public function testGetPorIdRetornaOSujeito(): void
    {
        $this->despachar('POST', '/subjects', ['id' => 'sujeito-1', 'nome' => 'Sujeito Um']);

        $response = $this->despachar('GET', '/subjects/sujeito-1');

        self::assertSame(200, $response->status());
        self::assertSame('sujeito-1', $this->decodificar($response)['data']['id']);
    }

    public function testGetPorIdInexistenteRetorna404(): void
    {
        $response = $this->despachar('GET', '/subjects/inexistente');

        self::assertSame(404, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }

    public function testPutAtualizaOSujeito(): void
    {
        $this->despachar('POST', '/subjects', ['id' => 'sujeito-1', 'nome' => 'Sujeito Um']);

        $response = $this->despachar('PUT', '/subjects/sujeito-1', ['nome' => 'Sujeito Renomeado']);

        self::assertSame(200, $response->status());
        self::assertSame('Sujeito Renomeado', $this->decodificar($response)['data']['nome']);
    }

    public function testPutEmSujeitoInexistenteRetorna404(): void
    {
        $response = $this->despachar('PUT', '/subjects/inexistente', ['nome' => 'Sujeito Renomeado']);

        self::assertSame(404, $response->status());
    }

    public function testDeleteRemoveOSujeitoERetorna204(): void
    {
        $this->despachar('POST', '/subjects', ['id' => 'sujeito-1', 'nome' => 'Sujeito Um']);

        $response = $this->despachar('DELETE', '/subjects/sujeito-1');

        self::assertSame(204, $response->status());
        self::assertSame('', $response->corpo());
        self::assertSame(404, $this->despachar('GET', '/subjects/sujeito-1')->status());
    }

    public function testDeleteEmSujeitoInexistenteRetorna404(): void
    {
        $response = $this->despachar('DELETE', '/subjects/inexistente');

        self::assertSame(404, $response->status());
    }
}
