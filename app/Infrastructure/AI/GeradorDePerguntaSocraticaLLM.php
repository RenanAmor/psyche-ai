<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\AI;

use PsycheAI\Infrastructure\Contracts\DTOs\ContextoConversaDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\LLMRequestDTO;
use PsycheAI\Infrastructure\Contracts\GeradorDePerguntaSocraticaInterface;
use PsycheAI\Infrastructure\Contracts\LLMInterface;
use Throwable;

/**
 * Motor de Enunciação Socrática — gera dinamicamente, via LLM, a pergunta
 * que o sistema devolve ao Sujeito em cada turno de /conversa (Sprint 23),
 * substituindo o template fixo/eco da Sprint 17 por continuidade real com
 * o que o Sujeito acabou de trazer ("jogo de cintura", decisão do
 * usuário). Fundamento: Documento-Mestre.md §6.7 ("o PsycheAI não fala
 * como quem sabe, fala como quem pergunta" — a maiêutica socrática) e as
 * Regras 7/9/10/11 (docs/Regras-Dominio.md): nunca afirmar, nunca
 * interpretar, nunca dar causa ou diagnóstico, nunca usar vocabulário
 * técnico (ato falho, metáfora, etc. são exclusivos das telas do
 * analista).
 *
 * O guardrail contra "conversar" virar "afirmar/interpretar" é de
 * sistema, não só de prompt: o output_config.format só admite um campo
 * (`pergunta`), e a resposta bruta nunca é confiada — só é aceita se for
 * JSON válido com esse campo e o texto terminar em "?" (checagem
 * determinística de forma, mesmo espírito do enum fechado do Motor
 * Freud). Qualquer desvio (JSON inválido, campo ausente, texto que não é
 * pergunta, falha de rede/API) devolve null — nunca propaga exceção, nunca
 * deixa texto fora de formato chegar ao Sujeito; o chamador trata null
 * como "use o fallback determinístico" (RespostaEcoRecorrenciaService).
 *
 * Decisão do usuário: sem filtro léxico adicional (ex.: lista de
 * marcadores de afirmação) — a fidelidade ao método socrático vem do
 * prompt/design da experiência, não de moderação de conteúdo a
 * posteriori.
 */
final class GeradorDePerguntaSocraticaLLM implements GeradorDePerguntaSocraticaInterface
{
    private const SYSTEM_PROMPT = <<<'PROMPT'
        Você é o modo de enunciação socrático do PsycheAI. Sua única função
        é fazer UMA pergunta curta e aberta que dê continuidade ao que o
        Sujeito acabou de trazer na conversa — nunca uma afirmação.

        Regras absolutas, sem exceção:
        - Nunca afirme, explique, resuma ou interprete o que o Sujeito disse.
        - Nunca dê causa, hipótese, diagnóstico ou sentido para nada.
        - Nunca use vocabulário técnico/clínico (ex.: "ato falho", "metáfora",
          "recorrência", "inconsciente") — isso é exclusivo das telas do
          analista, o Sujeito nunca vê esse vocabulário.
        - Nunca elogie, valide ou julgue o conteúdo trazido.
        - A pergunta deve soar como uma conversa genuína, não um
          questionário nem um template repetido — use os turnos recentes
          abaixo para dar continuidade real ao assunto, não uma pergunta
          genérica desconectada do que foi dito.
        - Se o Sujeito voltou a falar em algo que já apareceu antes na
          conversa, você pode notar essa volta (ex.: "Você voltou a falar
          nisso.") sem jamais dizer por quê ela acontece.

        Responda SOMENTE com a pergunta em si, terminando em "?".
        PROMPT;

    private const SCHEMA = [
        'type' => 'json_schema',
        'schema' => [
            'type' => 'object',
            'properties' => [
                'pergunta' => ['type' => 'string'],
            ],
            'required' => ['pergunta'],
            'additionalProperties' => false,
        ],
    ];

    public function __construct(
        private readonly LLMInterface $llm
    ) {
    }

    public function gerar(string $mensagemUsuario, ContextoConversaDTO $contexto): ?string
    {
        try {
            $resposta = $this->llm->complete(new LLMRequestDTO(
                prompt: self::SYSTEM_PROMPT . "\n\n" . $this->montarContexto($mensagemUsuario, $contexto),
                options: ['outputConfig' => self::SCHEMA]
            ));

            $decodificado = json_decode($resposta->content, true, flags: JSON_THROW_ON_ERROR);

            if (!is_array($decodificado) || !isset($decodificado['pergunta']) || !is_string($decodificado['pergunta'])) {
                return null;
            }

            $pergunta = trim($decodificado['pergunta']);

            if ($pergunta === '' || !str_ends_with($pergunta, '?')) {
                return null;
            }

            return $pergunta;
        } catch (Throwable) {
            return null;
        }
    }

    private function montarContexto(string $mensagemUsuario, ContextoConversaDTO $contexto): string
    {
        if ($contexto->turnosRecentes === []) {
            $transcricao = 'Esta é a primeira mensagem desta sessão.';
        } else {
            $transcricao = implode("\n", array_map(
                static fn (array $turno): string => sprintf('%s: %s', $turno['autor'], $turno['conteudo']),
                $contexto->turnosRecentes
            ));
        }

        $notaRepeticao = $contexto->ehRepeticao
            ? sprintf("\n\nO Sujeito já falou em algo parecido com \"%s\" antes nesta conversa.", $contexto->descricaoRecorrencia)
            : '';

        return sprintf(
            "Turnos recentes da conversa:\n%s\n\nMensagem atual do Sujeito: %s%s",
            $transcricao,
            $mensagemUsuario,
            $notaRepeticao
        );
    }
}
