<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

use DateTimeImmutable;

final class LinhaDoTempoEndpointsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->provider->sujeitos()->criar('sujeito-1', 'Sujeito Um');
        $this->provider->sessoes()->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));
        $this->provider->sessoes()->criar('sujeito-1', 'sessao-2', new DateTimeImmutable('2026-01-20 10:00:00'));
        $this->provider->discursos()->criar('sessao-1', 'discurso-1', 'Conteúdo A');
        $this->provider->discursos()->adicionarEvento('discurso-1', 'evento-1', 'Lapso', 0);
        $this->provider->memorias()->criar($this->provider->sujeitoRepository()->findById('sujeito-1'), 'memoria-1');
    }

    public function testGetTimelineDevolveTodosOsItensOrdenadosCronologicamente(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/timeline');

        $corpo = $this->decodificar($response);

        self::assertSame(200, $response->status());
        self::assertSame(5, $corpo['data']['total']);
        self::assertSame(
            ['sessao', 'discurso', 'sessao', 'memoria', 'evento'],
            array_column($corpo['data']['itens'], 'tipo')
        );
    }

    public function testGetTimelineComSujeitoInexistenteRetorna404(): void
    {
        $response = $this->despachar('GET', '/subjects/inexistente/timeline');

        self::assertSame(404, $response->status());
    }

    public function testGetTimelineFiltraPorTipo(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/timeline', [], ['tipo' => 'evento']);

        $corpo = $this->decodificar($response);

        self::assertSame(200, $response->status());
        self::assertSame(1, $corpo['data']['total']);
        self::assertSame('evento-1', $corpo['data']['itens'][0]['id']);
    }

    public function testGetTimelineComTipoInvalidoRetorna400(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/timeline', [], ['tipo' => 'inexistente']);

        self::assertSame(400, $response->status());
    }

    public function testGetTimelineFiltraPorIntervaloDeData(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/timeline', [], [
            'de' => '2026-01-15',
            'ate' => '2026-01-25',
        ]);

        $corpo = $this->decodificar($response);

        self::assertSame(200, $response->status());
        self::assertSame(2, $corpo['data']['total']);
        self::assertSame(['sessao-2', 'memoria-1'], array_column($corpo['data']['itens'], 'id'));
    }

    public function testGetTimelineComDeMaiorQueAteRetorna400(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/timeline', [], [
            'de' => '2026-02-01',
            'ate' => '2026-01-01',
        ]);

        self::assertSame(400, $response->status());
    }

    public function testGetTimelineComDataInvalidaRetorna400(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/timeline', [], ['de' => 'não-é-uma-data']);

        self::assertSame(400, $response->status());
    }

    public function testGetTimelineFiltraPorTexto(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/timeline', [], ['q' => 'Lapso']);

        $corpo = $this->decodificar($response);

        self::assertSame(1, $corpo['data']['total']);
        self::assertSame('evento-1', $corpo['data']['itens'][0]['id']);
    }

    public function testGetTimelinePagina(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/timeline', [], ['pagina' => '2', 'porPagina' => '2']);

        $corpo = $this->decodificar($response);

        self::assertSame(200, $response->status());
        self::assertSame(5, $corpo['data']['total']);
        self::assertSame(3, $corpo['data']['totalDePaginas']);
        self::assertSame(2, $corpo['data']['pagina']);
        self::assertCount(2, $corpo['data']['itens']);
    }

    public function testGetTimelineComPorPaginaAcimaDoLimiteRetorna400(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/timeline', [], ['porPagina' => '1000']);

        self::assertSame(400, $response->status());
    }

    public function testGetTimelineComPaginaNaoNumericaRetorna400(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/timeline', [], ['pagina' => 'abc']);

        self::assertSame(400, $response->status());
    }

    public function testGetConsolidationDevolveAsContagens(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/consolidation');

        $corpo = $this->decodificar($response);

        self::assertSame(200, $response->status());
        self::assertSame('sujeito-1', $corpo['data']['sujeitoId']);
        self::assertSame(2, $corpo['data']['quantidadeDeSessoes']);
        self::assertSame(1, $corpo['data']['quantidadeDeDiscursos']);
        self::assertSame(1, $corpo['data']['quantidadeDeEventosDiscursivos']);
        self::assertSame(1, $corpo['data']['quantidadeDeMemorias']);
    }

    public function testGetConsolidationComSujeitoInexistenteRetorna404(): void
    {
        $response = $this->despachar('GET', '/subjects/inexistente/consolidation');

        self::assertSame(404, $response->status());
    }
}
