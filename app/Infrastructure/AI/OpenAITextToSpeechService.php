<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\AI;

use PsycheAI\Infrastructure\Contracts\SpeechSynthesisInterface;
use RuntimeException;

/**
 * Primeira implementação concreta de SpeechSynthesisInterface: chama a
 * API de síntese de voz da OpenAI (`tts-1`, `response_format=mp3`) e
 * devolve os bytes do áudio. Mesmo padrão de OpenAIWhisperTranscriptionService
 * (par assimétrico desta): chave lida de variável de ambiente
 * (OPENAI_API_KEY, já usada pelo Whisper), sem estado entre chamadas.
 */
final class OpenAITextToSpeechService implements SpeechSynthesisInterface
{
    private const ENDPOINT = 'https://api.openai.com/v1/audio/speech';
    private const MODELO = 'tts-1';

    public function __construct(
        private readonly ?string $apiKey = null,
        private readonly string $voz = 'alloy'
    ) {
    }

    public function synthesize(string $texto): string
    {
        $chave = $this->apiKey ?? (getenv('OPENAI_API_KEY') ?: '');

        $handle = curl_init(self::ENDPOINT);

        curl_setopt_array($handle, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $chave,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'model' => self::MODELO,
                'voice' => $this->voz,
                'input' => $texto,
                'response_format' => 'mp3',
            ], JSON_THROW_ON_ERROR),
        ]);

        $corpoResposta = curl_exec($handle);
        $erroCurl = curl_errno($handle);
        $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        curl_close($handle);

        if ($erroCurl !== 0 || $corpoResposta === false) {
            throw new RuntimeException('Falha de comunicação com o serviço de síntese de voz.');
        }

        if ($status < 200 || $status >= 300) {
            throw new RuntimeException('O serviço de síntese de voz devolveu uma resposta inválida.');
        }

        return (string) $corpoResposta;
    }
}
