<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

final class ListaDeEsperaEndpointsTest extends HttpTestCase
{
    /**
     * @return array<string, mixed>
     */
    private function camposValidos(string $email = 'interessado@psyche.ai'): array
    {
        return [
            'email' => $email,
            'nome' => 'Ana Interessada',
            'profissao' => 'Psicóloga',
            'instituicao' => 'Universidade Federal',
            'paisEstado' => 'Brasil/SP',
            'motivoInteresse' => 'Quero participar da pesquisa.',
            'aceitouPoliticaPrivacidade' => '1',
            'aceitouTermoConsentimento' => '1',
        ];
    }

    public function testPostInscreveERetorna201ComOsDados(): void
    {
        $response = $this->despachar('POST', '/waitlist', $this->camposValidos());

        $corpo = $this->decodificar($response);

        self::assertSame(201, $response->status());
        self::assertSame('interessado@psyche.ai', $corpo['data']['email']);
        self::assertSame('Ana Interessada', $corpo['data']['nome']);
        self::assertSame('Psicóloga', $corpo['data']['profissao']);
        self::assertSame('Universidade Federal', $corpo['data']['instituicao']);
        self::assertSame('Brasil/SP', $corpo['data']['paisEstado']);
        self::assertSame('Quero participar da pesquisa.', $corpo['data']['motivoInteresse']);
        self::assertTrue($corpo['data']['aceitouPoliticaPrivacidade']);
        self::assertTrue($corpo['data']['aceitouTermoConsentimento']);
        self::assertNotSame('', $corpo['data']['id']);
        self::assertNotSame('', $corpo['data']['criadoEm']);
    }

    public function testPostComOrigemEcopsyRetorna201NormalmenteOrigemNaoAfetaOPayload(): void
    {
        $response = $this->despachar('POST', '/waitlist', array_merge($this->camposValidos(), ['origem' => 'ecopsy']));

        $corpo = $this->decodificar($response);

        self::assertSame(201, $response->status());
        self::assertSame('interessado@psyche.ai', $corpo['data']['email']);
        self::assertArrayNotHasKey('origem', $corpo['data']);
    }

    public function testPostSemProfissaoNemInstituicaoRetorna201ComCamposNulos(): void
    {
        $campos = $this->camposValidos();
        unset($campos['profissao'], $campos['instituicao']);

        $response = $this->despachar('POST', '/waitlist', $campos);

        $corpo = $this->decodificar($response);

        self::assertSame(201, $response->status());
        self::assertNull($corpo['data']['profissao']);
        self::assertNull($corpo['data']['instituicao']);
    }

    public function testPostComEmailInvalidoRetorna422(): void
    {
        $response = $this->despachar('POST', '/waitlist', array_merge($this->camposValidos(), ['email' => 'nao-e-email']));

        self::assertSame(422, $response->status());
        self::assertFalse($this->decodificar($response)['success']);
    }

    public function testPostComCampoObrigatorioAusenteRetorna400(): void
    {
        $response = $this->despachar('POST', '/waitlist', ['email' => 'interessado@psyche.ai']);

        self::assertSame(400, $response->status());
    }

    public function testPostSemAceitarAPoliticaDePrivacidadeRetorna400(): void
    {
        $campos = $this->camposValidos();
        unset($campos['aceitouPoliticaPrivacidade']);

        $response = $this->despachar('POST', '/waitlist', $campos);

        self::assertSame(400, $response->status());
    }

    public function testPostSemAceitarOTermoDeConsentimentoRetorna400(): void
    {
        $campos = $this->camposValidos();
        unset($campos['aceitouTermoConsentimento']);

        $response = $this->despachar('POST', '/waitlist', $campos);

        self::assertSame(400, $response->status());
    }

    public function testPostComEmailJaInscritoNaoDuplicaERetornaOMesmoId(): void
    {
        $primeira = $this->decodificar($this->despachar('POST', '/waitlist', $this->camposValidos()));
        $segunda = $this->decodificar($this->despachar('POST', '/waitlist', $this->camposValidos()));

        self::assertSame($primeira['data']['id'], $segunda['data']['id']);
    }

    public function testGetListaTodasAsInscricoes(): void
    {
        $this->despachar('POST', '/waitlist', $this->camposValidos('um@psyche.ai'));
        $this->despachar('POST', '/waitlist', $this->camposValidos('dois@psyche.ai'));

        $response = $this->despachar('GET', '/waitlist');

        self::assertSame(200, $response->status());
        self::assertCount(2, $this->decodificar($response)['data']);
    }
}
