<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

use DateTimeImmutable;
use PsycheAI\Infrastructure\Contracts\DTOs\TranscriptionResultDTO;
use PsycheAI\Infrastructure\Persistence\SQLite\Connection;
use PsycheAI\Infrastructure\Providers\ApplicationServiceProvider;
use PsycheAI\Presentation\Http\ExceptionHandler;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Http\Response;
use PsycheAI\Presentation\Http\Router;
use PsycheAI\Presentation\Routes;
use PsycheAI\Tests\Support\StorageStub;
use PsycheAI\Tests\Support\TranscricaoStub;
use Throwable;

/**
 * Não estende HttpTestCase::setUp() diretamente: o provider padrão usa
 * LocalFilesystemStorage/OpenAIWhisperTranscriptionService de verdade
 * (Sprint 22), e este teste precisa dos duplos em memória para não gravar
 * áudio real em disco nem depender de rede.
 */
final class GravacaoAudioEndpointsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        $pdo = (new Connection(':memory:'))->pdo();
        $this->provider = ApplicationServiceProvider::comPDO(
            $pdo,
            storage: new StorageStub(),
            transcricao: new TranscricaoStub()
        );

        $this->router = new Router();
        Routes::registrar($this->router, $this->provider);

        $this->provider->sujeitos()->criar('sujeito-1', 'Sujeito Um');
        $this->provider->sessoes()->criar('sujeito-1', 'sessao-1', new DateTimeImmutable('2026-07-31 10:00:00'));
    }

    private function despacharBinario(Request $request): Response
    {
        try {
            return $this->router->despachar($request);
        } catch (Throwable $erro) {
            return ExceptionHandler::converter($erro);
        }
    }

    public function testPostEnviaOAudioERetorna201(): void
    {
        $request = Request::criarComCorpoBinario('POST', '/sessions/sessao-1/audio', 'bytes-do-audio');

        $response = $this->despacharBinario($request);

        self::assertSame(201, $response->status());

        $corpo = json_decode($response->corpo(), true);
        self::assertSame('sessao-1', $corpo['data']['sessaoId']);
        self::assertSame('pendente', $corpo['data']['status']);
    }

    public function testPostComCorpoVazioRetorna400(): void
    {
        $request = Request::criarComCorpoBinario('POST', '/sessions/sessao-1/audio', '');

        $response = $this->despacharBinario($request);

        self::assertSame(400, $response->status());
    }

    public function testPostComSessaoInexistenteRetorna404(): void
    {
        $request = Request::criarComCorpoBinario('POST', '/sessions/inexistente/audio', 'bytes');

        $response = $this->despacharBinario($request);

        self::assertSame(404, $response->status());
    }

    public function testGetDevolveOsBytesOriginaisComContentTypeDeAudio(): void
    {
        $this->despacharBinario(Request::criarComCorpoBinario('POST', '/sessions/sessao-1/audio', 'bytes-originais'));

        $response = $this->despacharBinario(Request::criar('GET', '/sessions/sessao-1/audio'));

        self::assertSame(200, $response->status());
        self::assertSame('bytes-originais', $response->corpo());
        self::assertSame('audio/webm', $response->headers()['Content-Type']);
    }

    public function testGetSemGravacaoRetorna404(): void
    {
        $response = $this->despacharBinario(Request::criar('GET', '/sessions/sessao-1/audio'));

        self::assertSame(404, $response->status());
    }

    /**
     * POST /audio/transcricao (Sprint 32, Interface de Voz da ECO) não
     * depende de Sessao/GravacaoAudio nenhuma — por isso monta seu próprio
     * provider com uma TranscricaoStub específica, em vez de reaproveitar
     * a instância vazia de setUp() usada pelos demais testes desta classe.
     */
    public function testTranscricaoDevolveOTextoTranscritoSemCriarGravacao(): void
    {
        $pdo = (new Connection(':memory:'))->pdo();
        $provider = ApplicationServiceProvider::comPDO(
            $pdo,
            storage: new StorageStub(),
            transcricao: new TranscricaoStub(new TranscriptionResultDTO(text: 'fala transcrita do turno'))
        );
        $router = new Router();
        Routes::registrar($router, $provider);

        $request = Request::criarComCorpoBinario('POST', '/audio/transcricao', 'bytes-do-turno');

        try {
            $response = $router->despachar($request);
        } catch (Throwable $erro) {
            $response = ExceptionHandler::converter($erro);
        }

        self::assertSame(200, $response->status());

        $corpo = json_decode($response->corpo(), true);
        self::assertSame('fala transcrita do turno', $corpo['data']['texto']);
    }

    public function testTranscricaoComCorpoVazioRetorna400(): void
    {
        $request = Request::criarComCorpoBinario('POST', '/audio/transcricao', '');

        $response = $this->despacharBinario($request);

        self::assertSame(400, $response->status());
    }
}
