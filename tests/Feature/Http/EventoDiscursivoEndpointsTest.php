<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

final class EventoDiscursivoEndpointsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->provider->sujeitos()->criar('sujeito-1', 'Sujeito Um');
        $this->provider->sessoes()->criar('sujeito-1', 'sessao-1', new \DateTimeImmutable('2026-01-10 10:00:00'));
        $this->provider->discursos()->criar('sessao-1', 'discurso-1', 'Conteúdo do discurso');
    }

    public function testPostCriaUmEventoERetorna201ComODiscursoIdAssociado(): void
    {
        $response = $this->despachar('POST', '/events', [
            'discursoId' => 'discurso-1',
            'id' => 'evento-1',
            'conteudo' => 'Lapso',
            'posicao' => 3,
        ]);

        $corpo = $this->decodificar($response);

        self::assertSame(201, $response->status());
        self::assertSame('evento-1', $corpo['data']['id']);
        self::assertSame('discurso-1', $corpo['data']['discursoId']);
        self::assertSame('sessao-1', $corpo['data']['sessaoId']);
        self::assertSame('Lapso', $corpo['data']['conteudo']);
        self::assertSame(3, $corpo['data']['posicao']);
        self::assertNotSame('', $corpo['data']['criadoEm']);
    }

    public function testPostComDiscursoInexistenteRetorna404(): void
    {
        $response = $this->despachar('POST', '/events', [
            'discursoId' => 'discurso-inexistente',
            'id' => 'evento-1',
            'conteudo' => 'Lapso',
            'posicao' => 3,
        ]);

        self::assertSame(404, $response->status());
    }

    public function testPostComPosicaoNaoNumericaRetorna400(): void
    {
        $response = $this->despachar('POST', '/events', [
            'discursoId' => 'discurso-1',
            'id' => 'evento-1',
            'conteudo' => 'Lapso',
            'posicao' => 'abc',
        ]);

        self::assertSame(400, $response->status());
    }

    public function testPostComIdJaExistenteRetorna409(): void
    {
        $this->despachar('POST', '/events', ['discursoId' => 'discurso-1', 'id' => 'evento-1', 'conteudo' => 'Lapso', 'posicao' => 3]);

        $response = $this->despachar('POST', '/events', ['discursoId' => 'discurso-1', 'id' => 'evento-1', 'conteudo' => 'Outro', 'posicao' => 4]);

        self::assertSame(409, $response->status());
    }

    public function testGetListaTodosOsEventos(): void
    {
        $this->despachar('POST', '/events', ['discursoId' => 'discurso-1', 'id' => 'evento-1', 'conteudo' => 'Lapso', 'posicao' => 3]);
        $this->despachar('POST', '/events', ['discursoId' => 'discurso-1', 'id' => 'evento-2', 'conteudo' => 'Chiste', 'posicao' => 7]);

        $response = $this->despachar('GET', '/events');

        self::assertSame(200, $response->status());
        self::assertCount(2, $this->decodificar($response)['data']);
    }

    public function testGetPorIdInexistenteRetorna404(): void
    {
        $response = $this->despachar('GET', '/events/inexistente');

        self::assertSame(404, $response->status());
    }

    public function testPutAtualizaConteudoEPosicao(): void
    {
        $this->despachar('POST', '/events', ['discursoId' => 'discurso-1', 'id' => 'evento-1', 'conteudo' => 'Lapso', 'posicao' => 3]);

        $response = $this->despachar('PUT', '/events/evento-1', ['conteudo' => 'Lapso revisado', 'posicao' => 5]);

        $corpo = $this->decodificar($response);

        self::assertSame(200, $response->status());
        self::assertSame('Lapso revisado', $corpo['data']['conteudo']);
        self::assertSame(5, $corpo['data']['posicao']);
        self::assertSame('discurso-1', $corpo['data']['discursoId']);
    }

    public function testPutPreservaODataDeCriacaoOriginal(): void
    {
        $this->despachar('POST', '/events', ['discursoId' => 'discurso-1', 'id' => 'evento-1', 'conteudo' => 'Lapso', 'posicao' => 3]);

        $criadoEmOriginal = $this->decodificar($this->despachar('GET', '/events/evento-1'))['data']['criadoEm'];

        $response = $this->despachar('PUT', '/events/evento-1', ['conteudo' => 'Lapso revisado', 'posicao' => 5]);

        self::assertSame($criadoEmOriginal, $this->decodificar($response)['data']['criadoEm']);
    }

    public function testPutComPosicaoNegativaRetorna422(): void
    {
        $this->despachar('POST', '/events', ['discursoId' => 'discurso-1', 'id' => 'evento-1', 'conteudo' => 'Lapso', 'posicao' => 3]);

        $response = $this->despachar('PUT', '/events/evento-1', ['conteudo' => 'Lapso', 'posicao' => -1]);

        self::assertSame(422, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }

    public function testPutEmEventoInexistenteRetorna404(): void
    {
        $response = $this->despachar('PUT', '/events/inexistente', ['conteudo' => 'Lapso', 'posicao' => 1]);

        self::assertSame(404, $response->status());
    }

    public function testDeleteRemoveOEventoERetorna204(): void
    {
        $this->despachar('POST', '/events', ['discursoId' => 'discurso-1', 'id' => 'evento-1', 'conteudo' => 'Lapso', 'posicao' => 3]);

        $response = $this->despachar('DELETE', '/events/evento-1');

        self::assertSame(204, $response->status());
        self::assertSame(404, $this->despachar('GET', '/events/evento-1')->status());
    }

    public function testDeleteEmEventoInexistenteRetorna404(): void
    {
        $response = $this->despachar('DELETE', '/events/inexistente');

        self::assertSame(404, $response->status());
    }
}
