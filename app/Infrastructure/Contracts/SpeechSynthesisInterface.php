<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

/**
 * Porta de síntese de voz (texto para áudio), independente de provedor —
 * simétrica a TranscriptionInterface (áudio para texto).
 */
interface SpeechSynthesisInterface
{
    /**
     * @return string bytes brutos do áudio sintetizado (mp3)
     */
    public function synthesize(string $texto): string;
}
