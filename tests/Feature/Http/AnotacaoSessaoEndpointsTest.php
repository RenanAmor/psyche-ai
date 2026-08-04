<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

final class AnotacaoSessaoEndpointsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->provider->sujeitos()->criar('sujeito-1', 'Sujeito Um');
        $this->despachar('POST', '/sessions', [
            'sujeitoId' => 'sujeito-1',
            'id' => 'sessao-1',
            'data' => '2026-01-10 10:00:00',
        ]);
    }

    public function testGetSemAnotacaoAindaRetorna200ComConteudoVazio(): void
    {
        $response = $this->despachar('GET', '/sessions/sessao-1/annotation');

        self::assertSame(200, $response->status());
        $corpo = $this->decodificar($response);
        self::assertSame('', $corpo['data']['conteudo']);
        self::assertNull($corpo['data']['atualizadoEm']);
    }

    public function testPutCriaAAnotacao(): void
    {
        $response = $this->despachar('PUT', '/sessions/sessao-1/annotation', ['conteudo' => 'Paciente relatou ansiedade.']);

        self::assertSame(200, $response->status());
        $corpo = $this->decodificar($response);
        self::assertSame('Paciente relatou ansiedade.', $corpo['data']['conteudo']);
        self::assertNotNull($corpo['data']['atualizadoEm']);
    }

    public function testPutSubsequenteAtualizaEmVezDeDuplicar(): void
    {
        $this->despachar('PUT', '/sessions/sessao-1/annotation', ['conteudo' => 'Primeira versão.']);
        $this->despachar('PUT', '/sessions/sessao-1/annotation', ['conteudo' => 'Segunda versão.']);

        $response = $this->despachar('GET', '/sessions/sessao-1/annotation');

        self::assertSame('Segunda versão.', $this->decodificar($response)['data']['conteudo']);
    }

    public function testPutAceitaConteudoVazio(): void
    {
        $response = $this->despachar('PUT', '/sessions/sessao-1/annotation', ['conteudo' => 'texto']);
        self::assertSame(200, $response->status());

        $response = $this->despachar('PUT', '/sessions/sessao-1/annotation', ['conteudo' => '']);

        self::assertSame(200, $response->status());
        self::assertSame('', $this->decodificar($response)['data']['conteudo']);
    }

    public function testPutEmSessaoInexistenteRetorna404(): void
    {
        $response = $this->despachar('PUT', '/sessions/inexistente/annotation', ['conteudo' => 'x']);

        self::assertSame(404, $response->status());
    }
}
