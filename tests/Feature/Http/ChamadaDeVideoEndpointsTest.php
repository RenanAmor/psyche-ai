<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

use PsycheAI\Infrastructure\Persistence\SQLite\Connection;
use PsycheAI\Infrastructure\Providers\ApplicationServiceProvider;
use PsycheAI\Presentation\Routes;
use PsycheAI\Presentation\Http\Router;
use PsycheAI\Tests\Support\FakeVideoConferenceService;

final class ChamadaDeVideoEndpointsTest extends HttpTestCase
{
    private FakeVideoConferenceService $videoConference;

    protected function setUp(): void
    {
        $pdo = (new Connection(':memory:'))->pdo();
        $this->videoConference = new FakeVideoConferenceService();
        $this->provider = ApplicationServiceProvider::comPDO($pdo, videoConference: $this->videoConference);

        $this->router = new Router();
        Routes::registrar($this->router, $this->provider);

        $this->provider->sujeitos()->criar('sujeito-1', 'Sujeito Um');
        $this->provider->sessoes()->criar('sujeito-1', 'sessao-1', new \DateTimeImmutable('2026-01-10 10:00:00'));
    }

    public function testPostIniciarCriaAChamadaERetornaLinkETokens(): void
    {
        $response = $this->despachar('POST', '/sessions/sessao-1/videocall');

        self::assertSame(200, $response->status());
        $corpo = $this->decodificar($response);

        self::assertSame('sessao-1', $corpo['data']['chamada']['sessaoId']);
        self::assertNotEmpty($corpo['data']['chamada']['salaUrl']);
        self::assertNotEmpty($corpo['data']['tokenAnalista']);
        self::assertNotEmpty($corpo['data']['tokenAcesso']);
        self::assertSame('criada', $corpo['data']['chamada']['status']);
    }

    public function testPostIniciarDuasVezesReaproveitaAMesmaChamada(): void
    {
        $primeira = $this->decodificar($this->despachar('POST', '/sessions/sessao-1/videocall'));
        $segunda = $this->decodificar($this->despachar('POST', '/sessions/sessao-1/videocall'));

        self::assertSame($primeira['data']['chamada']['id'], $segunda['data']['chamada']['id']);
        self::assertSame($primeira['data']['tokenAcesso'], $segunda['data']['tokenAcesso']);
    }

    public function testPostIniciarEmSessaoInexistenteRetorna404(): void
    {
        $response = $this->despachar('POST', '/sessions/inexistente/videocall');

        self::assertSame(404, $response->status());
    }

    public function testPostJoinComTokenValidoDevolveSalaUrlETokenDoSujeito(): void
    {
        $iniciado = $this->decodificar($this->despachar('POST', '/sessions/sessao-1/videocall'));
        $token = $iniciado['data']['tokenAcesso'];

        $response = $this->despachar('POST', '/videocalls/' . $token . '/join');

        self::assertSame(200, $response->status());
        $corpo = $this->decodificar($response);
        self::assertSame($iniciado['data']['chamada']['salaUrl'], $corpo['data']['salaUrl']);
        self::assertNotEmpty($corpo['data']['tokenSujeito']);
    }

    public function testPostJoinComTokenInexistenteRetorna404(): void
    {
        $response = $this->despachar('POST', '/videocalls/token-que-nao-existe/join');

        self::assertSame(404, $response->status());
    }

    public function testPostEncerrarMarcaAChamadaEncerradaEChamaOProvedor(): void
    {
        $this->despachar('POST', '/sessions/sessao-1/videocall');

        $response = $this->despachar('POST', '/sessions/sessao-1/videocall/encerrar');

        self::assertSame(204, $response->status());
        self::assertNotEmpty($this->videoConference->salasEncerradas);
    }

    public function testPostJoinComTokenDeChamadaEncerradaRetorna404(): void
    {
        $iniciado = $this->decodificar($this->despachar('POST', '/sessions/sessao-1/videocall'));
        $token = $iniciado['data']['tokenAcesso'];

        $this->despachar('POST', '/sessions/sessao-1/videocall/encerrar');

        $response = $this->despachar('POST', '/videocalls/' . $token . '/join');

        self::assertSame(404, $response->status());
    }

    public function testPostEncerrarSemChamadaIniciadaRetorna404(): void
    {
        $response = $this->despachar('POST', '/sessions/sessao-1/videocall/encerrar');

        self::assertSame(404, $response->status());
    }
}
