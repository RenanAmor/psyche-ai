<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts\DTOs;

/**
 * Saída padronizada de qualquer provedor de transcrição de áudio.
 */
final class TranscriptionResultDTO
{
    /**
     * @param array<string, mixed> $metadata Dados adicionais específicos do
     *        provedor (ex.: language, confidence, durationSeconds).
     */
    public function __construct(
        public readonly string $text,
        public readonly array $metadata = []
    ) {
    }
}
