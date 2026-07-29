<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

use PsycheAI\Infrastructure\Contracts\DTOs\TranscriptionResultDTO;

/**
 * Porta de transcrição de áudio para texto, independente de provedor
 * (ex.: Whisper ou qualquer outro serviço de transcrição).
 */
interface TranscriptionInterface
{
    public function transcribe(string $audioPath): TranscriptionResultDTO;
}
