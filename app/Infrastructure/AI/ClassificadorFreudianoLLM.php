<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\AI;

use PsycheAI\Domain\ValueObjects\TipoFormacaoFreudiana;
use PsycheAI\Infrastructure\Contracts\ClassificadorEstruturalInterface;
use PsycheAI\Infrastructure\Contracts\DTOs\LLMRequestDTO;
use PsycheAI\Infrastructure\Contracts\LLMInterface;
use Throwable;

/**
 * Motor Freud — classificação estrutural via LLM (revisão pós-Sprint 17):
 * dá ao "atenção flutuante" o conhecimento conceitual de
 * Ontologia-Freud.md §3 necessário para reconhecer, na forma de um
 * conteúdo discursivo, qual das espécies de formação de compromisso (ou
 * a repetição) ele mais se assemelha — nunca para interpretar o que essa
 * forma significaria para o sujeito (Documento-Mestre.md §6.5).
 *
 * O guardrail contra "classificar forma" virar "interpretar conteúdo" é
 * de sistema, não de prompt: o output_config.format da chamada só admite
 * um campo (`tipo`), preso a um enum fechado de 6 strings — não há onde
 * o modelo colocar justificativa ou análise. Mesmo assim, a resposta
 * bruta nunca é confiada — é validada aqui contra
 * TipoFormacaoFreudiana::tryFrom(); qualquer coisa fora do esperado
 * (JSON inválido, valor fora do enum, falha de rede/API) cai em
 * NaoClassificado. Nenhum texto livre do modelo passa adiante.
 */
final class ClassificadorFreudianoLLM implements ClassificadorEstruturalInterface
{
    private const SYSTEM_PROMPT = <<<'PROMPT'
        Classifique o conteúdo discursivo a seguir em UMA das categorias
        estruturais abaixo, definidas em Ontologia-Freud.md §3. Classifique
        apenas a FORMA do conteúdo — nunca infira o que ele significaria
        para quem o disse.

        - ato_falho: erro de fala, ação, memória ou escrita que interrompe
          um ato conscientemente pretendido.
        - chiste: construção verbal que produz efeito de duplo sentido,
          condensação ou alusão.
        - sonho: relato de sonho.
        - repeticao: conteúdo que insiste, retornando ao mesmo ponto, sem
          elaboração nova.
        - formacao_de_compromisso: outra produção que combina dois
          sentidos ou impulsos em conflito, sem se encaixar nas anteriores.
        - nao_classificado: nenhuma das anteriores se aplica com clareza.
        PROMPT;

    private const SCHEMA = [
        'type' => 'json_schema',
        'schema' => [
            'type' => 'object',
            'properties' => [
                'tipo' => [
                    'type' => 'string',
                    'enum' => [
                        'ato_falho',
                        'chiste',
                        'sonho',
                        'repeticao',
                        'formacao_de_compromisso',
                        'nao_classificado',
                    ],
                ],
            ],
            'required' => ['tipo'],
            'additionalProperties' => false,
        ],
    ];

    public function __construct(
        private readonly LLMInterface $llm
    ) {
    }

    public function classificar(string $conteudo): TipoFormacaoFreudiana
    {
        try {
            $resposta = $this->llm->complete(new LLMRequestDTO(
                prompt: self::SYSTEM_PROMPT . "\n\nConteúdo: " . $conteudo,
                options: ['outputConfig' => self::SCHEMA]
            ));

            $decodificado = json_decode($resposta->content, true, flags: JSON_THROW_ON_ERROR);

            if (!is_array($decodificado) || !isset($decodificado['tipo']) || !is_string($decodificado['tipo'])) {
                return TipoFormacaoFreudiana::NaoClassificado;
            }

            return TipoFormacaoFreudiana::tryFrom($decodificado['tipo']) ?? TipoFormacaoFreudiana::NaoClassificado;
        } catch (Throwable) {
            return TipoFormacaoFreudiana::NaoClassificado;
        }
    }
}
