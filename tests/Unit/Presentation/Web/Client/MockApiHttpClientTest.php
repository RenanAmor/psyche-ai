<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Client;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Client\MockApiHttpClient;
use PsycheAI\Presentation\Web\Errors\ErrorType;

final class MockApiHttpClientTest extends TestCase
{
    public function testGetDevolveDadosMockadosParaRecursoConhecido(): void
    {
        $cliente = new MockApiHttpClient();

        $resposta = $cliente->get('/sujeitos');

        $this->assertTrue($resposta->sucesso);
        $this->assertNotEmpty($resposta->dados);
        $this->assertArrayHasKey('id', $resposta->dados[0]);
    }

    public function testGetDevolveListaVaziaParaEventosDiscursivosPorPadrao(): void
    {
        $cliente = new MockApiHttpClient();

        $resposta = $cliente->get('eventos-discursivos');

        $this->assertTrue($resposta->sucesso);
        $this->assertSame([], $resposta->dados);
    }

    public function testGetDevolveNaoEncontradoParaRecursoDesconhecido(): void
    {
        $cliente = new MockApiHttpClient();

        $resposta = $cliente->get('recurso-inexistente');

        $this->assertFalse($resposta->sucesso);
        $this->assertSame(ErrorType::NAO_ENCONTRADO, $resposta->erro?->tipo);
    }

    public function testComFalhaForcaTipoDeErroEmQualquerRecurso(): void
    {
        $cliente = MockApiHttpClient::comFalha(ErrorType::COMUNICACAO);

        $resposta = $cliente->get('sujeitos');

        $this->assertFalse($resposta->sucesso);
        $this->assertSame(ErrorType::COMUNICACAO, $resposta->erro?->tipo);
    }

    public function testPostDevolveOsDadosEnviadosComoSucesso(): void
    {
        $cliente = new MockApiHttpClient();

        $resposta = $cliente->post('sujeitos', ['nome' => 'Nova']);

        $this->assertTrue($resposta->sucesso);
        $this->assertSame(['nome' => 'Nova'], $resposta->dados);
    }

    public function testPostRespeitaFalhaForcada(): void
    {
        $cliente = MockApiHttpClient::comFalha(ErrorType::VALIDACAO);

        $resposta = $cliente->post('sujeitos', ['nome' => '']);

        $this->assertFalse($resposta->sucesso);
        $this->assertSame(ErrorType::VALIDACAO, $resposta->erro?->tipo);
    }

    public function testRecursosPersonalizadosPodemSerInjetados(): void
    {
        $cliente = new MockApiHttpClient(['sujeitos' => []]);

        $resposta = $cliente->get('sujeitos');

        $this->assertTrue($resposta->sucesso);
        $this->assertSame([], $resposta->dados);
    }
}
