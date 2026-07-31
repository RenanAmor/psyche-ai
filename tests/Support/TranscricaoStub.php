<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Support;

use PsycheAI\Infrastructure\Contracts\DTOs\TranscriptionResultDTO;
use PsycheAI\Infrastructure\Contracts\TranscriptionInterface;
use RuntimeException;

/**
 * Duplo de teste de TranscriptionInterface: devolve um resultado fixo (ou
 * lança uma falha simulada), sem nenhuma chamada de rede real —
 * OpenAIWhisperTranscriptionService é a implementação de produção,
 * verificada separadamente.
 */
final class TranscricaoStub implements TranscriptionInterface
{
    public function __construct(
        private readonly ?TranscriptionResultDTO $resultado = null,
        private readonly ?RuntimeException $falha = null
    ) {
    }

    public function transcribe(string $audioPath): TranscriptionResultDTO
    {
        if ($this->falha !== null) {
            throw $this->falha;
        }

        return $this->resultado ?? new TranscriptionResultDTO('');
    }
}
