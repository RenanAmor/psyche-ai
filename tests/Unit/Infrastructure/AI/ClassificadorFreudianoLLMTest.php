<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure\AI;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\ValueObjects\TipoFormacaoFreudiana;
use PsycheAI\Infrastructure\AI\ClassificadorFreudianoLLM;
use PsycheAI\Infrastructure\Contracts\ClassificadorEstruturalInterface;
use PsycheAI\Infrastructure\Contracts\DTOs\LLMRequestDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\LLMResponseDTO;
use PsycheAI\Infrastructure\Contracts\LLMInterface;
use RuntimeException;

/**
 * Este é o teste que prova o guardrail (Documento-Mestre.md §6.5): a
 * saída do LLM nunca é confiada às cegas — qualquer coisa fora do
 * vocabulário fechado de TipoFormacaoFreudiana cai em NaoClassificado,
 * nunca lança exceção para fora do adapter nem propaga texto livre.
 */
final class ClassificadorFreudianoLLMTest extends TestCase
{
    public function testImplementaClassificadorEstruturalInterface(): void
    {
        $classificador = new ClassificadorFreudianoLLM($this->llmQueDevolve('{"tipo":"chiste"}'));

        $this->assertInstanceOf(ClassificadorEstruturalInterface::class, $classificador);
    }

    public function testJsonValidoComTipoValidoClassificaCorretamente(): void
    {
        $classificador = new ClassificadorFreudianoLLM($this->llmQueDevolve('{"tipo":"ato_falho"}'));

        $this->assertSame(TipoFormacaoFreudiana::AtoFalho, $classificador->classificar('eu quis dizer outra coisa'));
    }

    public function testTipoForaDoVocabularioFechadoCaiEmNaoClassificado(): void
    {
        $classificador = new ClassificadorFreudianoLLM($this->llmQueDevolve('{"tipo":"hipotese_diagnostica"}'));

        $this->assertSame(TipoFormacaoFreudiana::NaoClassificado, $classificador->classificar('qualquer conteúdo'));
    }

    public function testTextoLivreNaoJsonCaiEmNaoClassificado(): void
    {
        $classificador = new ClassificadorFreudianoLLM(
            $this->llmQueDevolve('Isso parece revelar um conflito inconsciente sobre autoridade.')
        );

        $this->assertSame(TipoFormacaoFreudiana::NaoClassificado, $classificador->classificar('qualquer conteúdo'));
    }

    public function testJsonSemCampoTipoCaiEmNaoClassificado(): void
    {
        $classificador = new ClassificadorFreudianoLLM($this->llmQueDevolve('{"outraCoisa":"valor"}'));

        $this->assertSame(TipoFormacaoFreudiana::NaoClassificado, $classificador->classificar('qualquer conteúdo'));
    }

    public function testFalhaDeRedeOuApiCaiEmNaoClassificadoSemLancarExcecao(): void
    {
        $llm = new class implements LLMInterface {
            public function complete(LLMRequestDTO $request): LLMResponseDTO
            {
                throw new RuntimeException('falha simulada de rede/API');
            }
        };

        $classificador = new ClassificadorFreudianoLLM($llm);

        $this->assertSame(TipoFormacaoFreudiana::NaoClassificado, $classificador->classificar('qualquer conteúdo'));
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
