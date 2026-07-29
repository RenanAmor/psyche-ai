<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts\DTOs;

/**
 * Saída padronizada de qualquer provedor de LLM.
 */
final class LLMResponseDTO
{
    /**
     * @param array<string, mixed> $metadata Dados adicionais específicos do
     *        provedor (ex.: tokensUsed, model, finishReason).
     */
    public function __construct(
        public readonly string $content,
        public readonly array $metadata = []
    ) {
    }
}
