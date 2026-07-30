<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\AI;

use Anthropic\Client;
use PsycheAI\Infrastructure\Contracts\DTOs\LLMRequestDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\LLMResponseDTO;
use PsycheAI\Infrastructure\Contracts\LLMInterface;

/**
 * Primeira implementação concreta de LLMInterface no projeto (a interface
 * existe desde a Sprint 1, sem implementação, conforme docs/Roadmap.md e
 * docs/Arquitetura-Camadas.md). Adapter fino: só fala com a API da
 * Anthropic e devolve o texto bruto da resposta — não interpreta, não
 * valida contra nenhum vocabulário de domínio. Essa validação é
 * responsabilidade de quem consome este serviço (ex.:
 * ClassificadorFreudianoLLM), nunca deste adapter.
 *
 * $request->options pode conter 'model', 'maxTokens' e 'outputConfig'
 * (formato aceito por Client::messages->create()) — mantido como bag
 * opaco, igual à definição original de LLMRequestDTO.
 */
final class AnthropicLLMService implements LLMInterface
{
    private const MODELO_PADRAO = 'claude-haiku-4-5';
    private const MAX_TOKENS_PADRAO = 1024;

    private readonly Client $client;

    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client(apiKey: (string) (getenv('ANTHROPIC_API_KEY') ?: ''));
    }

    public function complete(LLMRequestDTO $request): LLMResponseDTO
    {
        $parametros = [
            'model' => $request->options['model'] ?? self::MODELO_PADRAO,
            'maxTokens' => $request->options['maxTokens'] ?? self::MAX_TOKENS_PADRAO,
            'messages' => [['role' => 'user', 'content' => $request->prompt]],
        ];

        if (isset($request->options['outputConfig'])) {
            $parametros['outputConfig'] = $request->options['outputConfig'];
        }

        $mensagem = $this->client->messages->create(...$parametros);

        $texto = '';

        foreach ($mensagem->content as $bloco) {
            if ($bloco->type === 'text') {
                $texto = $bloco->text;
                break;
            }
        }

        return new LLMResponseDTO(
            content: $texto,
            metadata: [
                'model' => $mensagem->model,
                'stopReason' => $mensagem->stopReason,
            ]
        );
    }
}
