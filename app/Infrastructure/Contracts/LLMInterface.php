<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

use PsycheAI\Infrastructure\Contracts\DTOs\LLMRequestDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\LLMResponseDTO;

/**
 * Porta de acesso a um provedor de LLM (ex.: OpenAI, Claude, Gemini),
 * independente de qual provedor concreto está por trás dela.
 */
interface LLMInterface
{
    public function complete(LLMRequestDTO $request): LLMResponseDTO;
}
