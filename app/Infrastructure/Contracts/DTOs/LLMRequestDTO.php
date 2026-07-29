<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts\DTOs;

/**
 * Entrada padronizada para qualquer provedor de LLM (OpenAI, Claude,
 * Gemini, etc.), sem acoplar o contrato a um provedor específico.
 */
final class LLMRequestDTO
{
    /**
     * @param array<string, mixed> $options Parâmetros específicos do provedor
     *        (ex.: temperature, maxTokens, model), mantidos como bag opaco
     *        para não acoplar o contrato a um provedor específico.
     */
    public function __construct(
        public readonly string $prompt,
        public readonly array $options = []
    ) {
    }
}
