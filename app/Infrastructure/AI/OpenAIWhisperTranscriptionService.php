<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\AI;

use CURLFile;
use PsycheAI\Infrastructure\Contracts\DTOs\TranscriptionResultDTO;
use PsycheAI\Infrastructure\Contracts\TranscriptionInterface;
use RuntimeException;

/**
 * Primeira implementação concreta de TranscriptionInterface (porta
 * definida desde a Sprint 7, sem implementação até a Sprint 22). Adapter
 * fino: chama a API de transcrição da OpenAI (`whisper-1`,
 * `response_format=verbose_json`, `temperature=0`) e devolve o texto e os
 * segmentos brutos — sem nenhuma correção/normalização da fala (a
 * verbosidade "crua" é deliberada: é o material que os Motores
 * Freud/Lacan precisam, ver Documento-Mestre.md e Regras-Dominio.md,
 * Regra 2). Não valida nem interpreta o conteúdo transcrito.
 *
 * Mesmo padrão de AnthropicLLMService: chave lida de variável de
 * ambiente, cliente HTTP injetável só para testes.
 */
final class OpenAIWhisperTranscriptionService implements TranscriptionInterface
{
    private const ENDPOINT = 'https://api.openai.com/v1/audio/transcriptions';
    private const MODELO = 'whisper-1';

    public function __construct(
        private readonly ?string $apiKey = null,
        private readonly string $idioma = 'pt'
    ) {
    }

    public function transcribe(string $audioPath): TranscriptionResultDTO
    {
        $chave = $this->apiKey ?? (getenv('OPENAI_API_KEY') ?: '');

        $handle = curl_init(self::ENDPOINT);

        curl_setopt_array($handle, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $chave],
            CURLOPT_POSTFIELDS => [
                'file' => new CURLFile($audioPath),
                'model' => self::MODELO,
                'response_format' => 'verbose_json',
                'temperature' => '0',
                'language' => $this->idioma,
            ],
        ]);

        $corpoResposta = curl_exec($handle);
        $erroCurl = curl_errno($handle);
        $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        curl_close($handle);

        if ($erroCurl !== 0 || $corpoResposta === false) {
            throw new RuntimeException('Falha de comunicação com o serviço de transcrição.');
        }

        $decodificado = json_decode((string) $corpoResposta, true);

        if ($status < 200 || $status >= 300 || !is_array($decodificado)) {
            throw new RuntimeException('O serviço de transcrição devolveu uma resposta inválida.');
        }

        return new TranscriptionResultDTO(
            text: (string) ($decodificado['text'] ?? ''),
            metadata: [
                'language' => $decodificado['language'] ?? null,
                'durationSeconds' => $decodificado['duration'] ?? null,
            ],
            segments: array_map(
                static fn (array $segmento): array => [
                    'text' => trim((string) $segmento['text']),
                    'inicio' => (float) $segmento['start'],
                    'fim' => (float) $segmento['end'],
                ],
                $decodificado['segments'] ?? []
            )
        );
    }
}
