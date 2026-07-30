<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

use DateTimeImmutable;

final class ObservacaoEndpointsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->provider->sujeitos()->criar('sujeito-1', 'Sujeito Um');
        $this->provider->sessoes()->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-01-10 10:00:00'));
        $this->provider->sessoes()->criar('sujeito-1', 'sessao-2', new DateTimeImmutable('2026-01-20 10:00:00'));
        $this->provider->discursos()->criar('sessao-1', 'discurso-1', 'Conteúdo A');
        $this->provider->discursos()->adicionarEvento('discurso-1', 'evento-1', 'lapso', 0);
        $this->provider->discursos()->criar('sessao-2', 'discurso-2', 'Conteúdo B');
        $this->provider->discursos()->adicionarEvento('discurso-2', 'evento-2', 'lapso', 0);
        $this->provider->discursos()->adicionarEvento('discurso-2', 'evento-3', 'chiste', 1);
    }

    public function testGetObservationsDevolveAsRecorrenciasEObservacoes(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/observations');

        $corpo = $this->decodificar($response);

        self::assertSame(200, $response->status());
        self::assertSame('sujeito-1', $corpo['data']['sujeitoId']);
        self::assertCount(1, $corpo['data']['recorrencias']);
        self::assertSame('lapso', $corpo['data']['recorrencias'][0]['descricao']);
        self::assertSame(2, $corpo['data']['recorrencias'][0]['frequencia']);
        self::assertCount(1, $corpo['data']['observacoes']);
        self::assertSame(
            'Recorrência observada: lapso (2 ocorrência(s)).',
            $corpo['data']['observacoes'][0]['texto']
        );
    }

    public function testGetObservationsComSujeitoInexistenteRetorna404(): void
    {
        $response = $this->despachar('GET', '/subjects/inexistente/observations');

        self::assertSame(404, $response->status());
    }

    public function testGetObservationsAceitaMinimoDeRecorrenciaPersonalizado(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/observations', [], ['minimoDeRecorrencia' => '1']);

        $corpo = $this->decodificar($response);

        self::assertSame(200, $response->status());
        self::assertCount(2, $corpo['data']['recorrencias']);
    }

    public function testGetObservationsComMinimoDeRecorrenciaInvalidoRetorna400(): void
    {
        $response = $this->despachar('GET', '/subjects/sujeito-1/observations', [], ['minimoDeRecorrencia' => 'abc']);

        self::assertSame(400, $response->status());
    }
}
