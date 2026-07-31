<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure\AI;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\AI\GeradorDePerguntaSocraticaLLM;
use PsycheAI\Infrastructure\Contracts\DTOs\ContextoConversaDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\LLMRequestDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\LLMResponseDTO;
use PsycheAI\Infrastructure\Contracts\GeradorDePerguntaSocraticaInterface;
use PsycheAI\Infrastructure\Contracts\LLMInterface;
use RuntimeException;

/**
 * Prova o guardrail estrutural (mesmo espírito do Motor Freud,
 * ClassificadorFreudianoLLMTest): a saída do LLM nunca é confiada às
 * cegas — só é aceita se for JSON válido, com o campo "pergunta", texto
 * não vazio e terminando em "?". Qualquer desvio devolve null, nunca
 * lança exceção nem propaga texto fora de formato.
 */
final class GeradorDePerguntaSocraticaLLMTest extends TestCase
{
    public function testImplementaGeradorDePerguntaSocraticaInterface(): void
    {
        $gerador = new GeradorDePerguntaSocraticaLLM($this->llmQueDevolve('{"pergunta":"O que vem à mente?"}'));

        $this->assertInstanceOf(GeradorDePerguntaSocraticaInterface::class, $gerador);
    }

    public function testJsonValidoTerminandoEmInterrogacaoDevolveAPerguntaVerbatim(): void
    {
        $gerador = new GeradorDePerguntaSocraticaLLM($this->llmQueDevolve('{"pergunta":"O que mais vem à mente sobre isso?"}'));

        $this->assertSame(
            'O que mais vem à mente sobre isso?',
            $gerador->gerar('Estou ansioso hoje.', $this->contextoVazio())
        );
    }

    public function testJsonValidoQueNaoTerminaEmInterrogacaoCaiEmNull(): void
    {
        $gerador = new GeradorDePerguntaSocraticaLLM($this->llmQueDevolve('{"pergunta":"Isso é sobre ansiedade."}'));

        $this->assertNull($gerador->gerar('Estou ansioso hoje.', $this->contextoVazio()));
    }

    public function testJsonSemCampoPerguntaCaiEmNull(): void
    {
        $gerador = new GeradorDePerguntaSocraticaLLM($this->llmQueDevolve('{"outraCoisa":"valor"}'));

        $this->assertNull($gerador->gerar('qualquer coisa', $this->contextoVazio()));
    }

    public function testTextoLivreNaoJsonCaiEmNull(): void
    {
        $gerador = new GeradorDePerguntaSocraticaLLM(
            $this->llmQueDevolve('O que vem à mente sobre isso?')
        );

        $this->assertNull($gerador->gerar('qualquer coisa', $this->contextoVazio()));
    }

    public function testPerguntaVaziaCaiEmNull(): void
    {
        $gerador = new GeradorDePerguntaSocraticaLLM($this->llmQueDevolve('{"pergunta":"   "}'));

        $this->assertNull($gerador->gerar('qualquer coisa', $this->contextoVazio()));
    }

    public function testFalhaDeRedeOuApiCaiEmNullSemLancarExcecao(): void
    {
        $llm = new class implements LLMInterface {
            public function complete(LLMRequestDTO $request): LLMResponseDTO
            {
                throw new RuntimeException('falha simulada de rede/API');
            }
        };

        $gerador = new GeradorDePerguntaSocraticaLLM($llm);

        $this->assertNull($gerador->gerar('qualquer coisa', $this->contextoVazio()));
    }

    private function contextoVazio(): ContextoConversaDTO
    {
        return new ContextoConversaDTO(turnosRecentes: [], ehRepeticao: false, descricaoRecorrencia: null);
    }

    private function llmQueDevolve(string $conteudoDaResposta): LLMInterface
    {
        return new class($conteudoDaResposta) implements LLMInterface {
            public function __construct(private readonly string $conteudoDaResposta)
            {
            }

            public function complete(LLMRequestDTO $request): LLMResponseDTO
            {
                return new LLMResponseDTO(content: $this->conteudoDaResposta);
            }
        };
    }
}
