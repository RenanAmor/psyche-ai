<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

final class DiscursoEndpointsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->provider->sujeitos()->criar('sujeito-1', 'Sujeito Um');
        $this->provider->sessoes()->criar('sujeito-1', 'sessao-1', new \DateTimeImmutable('2026-01-10 10:00:00'));
    }

    public function testPostCriaUmDiscursoERetorna201(): void
    {
        $response = $this->despachar('POST', '/discourses', [
            'sessaoId' => 'sessao-1',
            'id' => 'discurso-1',
            'conteudo' => 'Conteúdo do discurso',
        ]);

        $corpo = $this->decodificar($response);

        self::assertSame(201, $response->status());
        self::assertTrue($corpo['success']);
        self::assertSame('discurso-1', $corpo['data']['id']);
        self::assertSame('Conteúdo do discurso', $corpo['data']['conteudo']);
        self::assertSame(0, $corpo['data']['quantidadeDeEventos']);
    }

    public function testPostComSessaoInexistenteRetorna404(): void
    {
        $response = $this->despachar('POST', '/discourses', [
            'sessaoId' => 'sessao-inexistente',
            'id' => 'discurso-1',
            'conteudo' => 'Conteúdo',
        ]);

        self::assertSame(404, $response->status());
    }

    public function testPostComIdJaExistenteRetorna409(): void
    {
        $this->despachar('POST', '/discourses', ['sessaoId' => 'sessao-1', 'id' => 'discurso-1', 'conteudo' => 'Conteúdo']);

        $response = $this->despachar('POST', '/discourses', ['sessaoId' => 'sessao-1', 'id' => 'discurso-1', 'conteudo' => 'Outro']);

        self::assertSame(409, $response->status());
    }

    public function testGetListaTodosOsDiscursos(): void
    {
        $this->despachar('POST', '/discourses', ['sessaoId' => 'sessao-1', 'id' => 'discurso-1', 'conteudo' => 'Conteúdo 1']);
        $this->despachar('POST', '/discourses', ['sessaoId' => 'sessao-1', 'id' => 'discurso-2', 'conteudo' => 'Conteúdo 2']);

        $response = $this->despachar('GET', '/discourses');

        self::assertSame(200, $response->status());
        self::assertCount(2, $this->decodificar($response)['data']);
    }

    public function testGetPorIdInexistenteRetorna404(): void
    {
        $response = $this->despachar('GET', '/discourses/inexistente');

        self::assertSame(404, $response->status());
    }

    public function testPutAtualizaOConteudo(): void
    {
        $this->despachar('POST', '/discourses', ['sessaoId' => 'sessao-1', 'id' => 'discurso-1', 'conteudo' => 'Original']);

        $response = $this->despachar('PUT', '/discourses/discurso-1', ['conteudo' => 'Atualizado']);

        self::assertSame(200, $response->status());
        self::assertSame('Atualizado', $this->decodificar($response)['data']['conteudo']);
    }

    public function testPutComConteudoVazioRetorna400(): void
    {
        $this->despachar('POST', '/discourses', ['sessaoId' => 'sessao-1', 'id' => 'discurso-1', 'conteudo' => 'Original']);

        $response = $this->despachar('PUT', '/discourses/discurso-1', ['conteudo' => '']);

        self::assertSame(400, $response->status());
    }

    public function testDeleteRemoveODiscursoERetorna204(): void
    {
        $this->despachar('POST', '/discourses', ['sessaoId' => 'sessao-1', 'id' => 'discurso-1', 'conteudo' => 'Conteúdo']);

        $response = $this->despachar('DELETE', '/discourses/discurso-1');

        self::assertSame(204, $response->status());
        self::assertSame(404, $this->despachar('GET', '/discourses/discurso-1')->status());
    }
}
