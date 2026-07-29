<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Contracts\DTOs\TranscriptionResultDTO;
use PsycheAI\Infrastructure\Contracts\TranscriptionInterface;

final class TranscriptionInterfaceTest extends TestCase
{
    public function testImplementationTranscribesAnAudioPathIntoAResult(): void
    {
        $transcriber = new class implements TranscriptionInterface {
            public function transcribe(string $audioPath): TranscriptionResultDTO
            {
                return new TranscriptionResultDTO(
                    text: "transcrição de {$audioPath}",
                    metadata: ['language' => 'pt-BR']
                );
            }
        };

        $resultado = $transcriber->transcribe('sessoes/1.wav');

        $this->assertSame('transcrição de sessoes/1.wav', $resultado->text);
        $this->assertSame(['language' => 'pt-BR'], $resultado->metadata);
    }
}
