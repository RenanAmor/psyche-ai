<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Contracts\DTOs\LLMRequestDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\LLMResponseDTO;
use PsycheAI\Infrastructure\Contracts\LLMInterface;

final class LLMInterfaceTest extends TestCase
{
    public function testImplementationCompletesARequestIntoAResponse(): void
    {
        $llm = new class implements LLMInterface {
            public function complete(LLMRequestDTO $request): LLMResponseDTO
            {
                return new LLMResponseDTO(
                    content: "resposta para: {$request->prompt}",
                    metadata: ['model' => $request->options['model'] ?? 'desconhecido']
                );
            }
        };

        $request = new LLMRequestDTO(prompt: 'Resuma a sessão.', options: ['model' => 'modelo-x']);
        $response = $llm->complete($request);

        $this->assertSame('resposta para: Resuma a sessão.', $response->content);
        $this->assertSame(['model' => 'modelo-x'], $response->metadata);
    }
}
