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
     * @param array<int, array{text: string, inicio: float, fim: float}> $segments
     *        Trechos da gravação contínua, na ordem em que o provedor os
     *        detectou (ex.: por pausa/silêncio) — usado (Sprint 22) para
     *        dividir uma GravacaoAudio de sessão inteira em múltiplos
     *        EventoDiscursivo, um por segmento, sem precisar de detecção de
     *        pausa própria. Vazio para provedores que só devolvem texto
     *        corrido (`text`/`metadata` continuam válidos nesse caso).
     */
    public function __construct(
        public readonly string $text,
        public readonly array $metadata = [],
        public readonly array $segments = []
    ) {
    }
}
