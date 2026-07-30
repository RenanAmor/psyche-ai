<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

final class MensagemEndpointsTest extends HttpTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->provider->sujeitos()->criar('sujeito-1', 'Sujeito Um');
        $this->provider->sessoes()->criar('sujeito-1', 'sessao-1', new \DateTimeImmutable('2026-01-10 10:00:00'));
    }

    public function testPostRegistraAMensagemDoUsuarioEARespostaAutomaticaERetorna201(): void
    {
        $response = $this->despachar('POST', '/sessions/sessao-1/messages', [
            'conteudo' => 'Estou me sentindo ansioso hoje.',
        ]);

        $corpo = $this->decodificar($response);

        self::assertSame(201, $response->status());
        self::assertTrue($corpo['success']);
        self::assertSame('Estou me sentindo ansioso hoje.', $corpo['data']['mensagemUsuario']['conteudo']);
        self::assertSame(0, $corpo['data']['mensagemUsuario']['posicao']);
        self::assertSame('Recebi sua mensagem. Continue falando livremente.', $corpo['data']['respostaSistema']['conteudo']);
        self::assertSame(1, $corpo['data']['respostaSistema']['posicao']);
        self::assertSame('sessao-1', $corpo['data']['mensagemUsuario']['sessaoId']);
    }

    public function testAsMensagensFicamPersistidasEVisiveisViaGetEvents(): void
    {
        $this->despachar('POST', '/sessions/sessao-1/messages', ['conteudo' => 'Primeira mensagem.']);

        $response = $this->despachar('GET', '/events');
        $eventos = $this->decodificar($response)['data'];

        self::assertCount(2, $eventos);
    }

    public function testSegundoEnvioContinuaAPosicaoNoMesmoDiscurso(): void
    {
        $this->despachar('POST', '/sessions/sessao-1/messages', ['conteudo' => 'Primeira mensagem.']);
        $response = $this->despachar('POST', '/sessions/sessao-1/messages', ['conteudo' => 'Segunda mensagem.']);

        $corpo = $this->decodificar($response);

        self::assertSame(2, $corpo['data']['mensagemUsuario']['posicao']);
        self::assertSame(3, $corpo['data']['respostaSistema']['posicao']);

        $eventos = $this->decodificar($this->despachar('GET', '/events'))['data'];
        self::assertCount(4, $eventos);
    }

    public function testPostComSessaoInexistenteRetorna404(): void
    {
        $response = $this->despachar('POST', '/sessions/sessao-inexistente/messages', ['conteudo' => 'Olá']);

        self::assertSame(404, $response->status());
    }

    public function testPostComConteudoAusenteRetorna400(): void
    {
        $response = $this->despachar('POST', '/sessions/sessao-1/messages', []);

        self::assertSame(400, $response->status());
    }

    public function testPostComConteudoEmBrancoRetorna400(): void
    {
        $response = $this->despachar('POST', '/sessions/sessao-1/messages', ['conteudo' => '   ']);

        self::assertSame(400, $response->status());
    }
}
