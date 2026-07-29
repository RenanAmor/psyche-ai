<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Web;

use PsycheAI\Presentation\Web\Client\ApiHttpClient;
use PsycheAI\Presentation\Web\Errors\ErrorType;

/**
 * Exercita o mapeamento de ApiHttpClient para status HTTP que a API
 * saudável da Sprint 10 nunca produz por si mesma (500, corpo não-JSON,
 * 204 sem corpo) — usando um servidor HTTP real e mínimo
 * (fixtures/fake-status-server.php), não a aplicação e não um mock da
 * lógica de negócio.
 */
final class ApiHttpClientTest extends FakeApiServerTestCase
{
    public function testStatus500EMapeadoParaErroInterno(): void
    {
        $cliente = new ApiHttpClient(self::$baseUrl);

        $resposta = $cliente->get('erro-500');

        self::assertFalse($resposta->sucesso);
        self::assertSame(ErrorType::INTERNO, $resposta->erro?->tipo);
    }

    public function testCorpoNaoJsonEMapeadoParaErroInterno(): void
    {
        $cliente = new ApiHttpClient(self::$baseUrl);

        $resposta = $cliente->get('corpo-invalido');

        self::assertFalse($resposta->sucesso);
        self::assertSame(ErrorType::INTERNO, $resposta->erro?->tipo);
    }

    public function testStatus204EMapeadoParaSucessoComListaVazia(): void
    {
        $cliente = new ApiHttpClient(self::$baseUrl);

        $resposta = $cliente->delete('sem-conteudo');

        self::assertTrue($resposta->sucesso);
        self::assertSame([], $resposta->dados);
    }

    public function testStatus404EMapeadoParaNaoEncontrado(): void
    {
        $cliente = new ApiHttpClient(self::$baseUrl);

        $resposta = $cliente->get('rota-desconhecida');

        self::assertFalse($resposta->sucesso);
        self::assertSame(ErrorType::NAO_ENCONTRADO, $resposta->erro?->tipo);
    }

    public function testFalhaDeConexaoEMapeadaParaComunicacao(): void
    {
        $portaFechada = self::portaFechada();
        $cliente = new ApiHttpClient(sprintf('http://127.0.0.1:%d', $portaFechada), 1);

        $resposta = $cliente->get('qualquer-coisa');

        self::assertFalse($resposta->sucesso);
        self::assertSame(ErrorType::COMUNICACAO, $resposta->erro?->tipo);
    }
}
