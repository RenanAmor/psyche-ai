<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Support;

use PsycheAI\Infrastructure\Contracts\DTOs\TranscriptionResultDTO;
use PsycheAI\Infrastructure\Contracts\TranscriptionInterface;
use RuntimeException;

/**
 * Variante de TranscricaoStub que devolve um TranscriptionResultDTO
 * diferente a cada chamada, na ordem em que foram enfileirados — necessário
 * para testar processarTrilhasDeChamada(), que transcreve várias trilhas
 * (uma chamada a transcribe() por trilha) e precisa de resultados distintos
 * por trilha, não um único resultado fixo repetido.
 */
final class QueuedTranscricaoStub implements TranscriptionInterface
{
    /** @var TranscriptionResultDTO[] */
    private array $fila;

    public function __construct(TranscriptionResultDTO ...$resultados)
    {
        $this->fila = $resultados;
    }

    public function transcribe(string $audioPath): TranscriptionResultDTO
    {
        $proximo = array_shift($this->fila);

        if ($proximo === null) {
            throw new RuntimeException('QueuedTranscricaoStub: nenhum resultado enfileirado restante.');
        }

        return $proximo;
    }
}
