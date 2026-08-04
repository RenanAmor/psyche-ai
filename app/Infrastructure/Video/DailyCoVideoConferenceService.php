<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Video;

use PsycheAI\Infrastructure\Contracts\DTOs\RecordingDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\SalaCriadaDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\TrackDTO;
use PsycheAI\Infrastructure\Contracts\VideoConferenceInterface;
use RuntimeException;

/**
 * Adapter fino para a REST API do Daily.co — mesmo padrão de
 * OpenAIWhisperTranscriptionService (curl cru, sem SDK PHP oficial, chave
 * lida de variável de ambiente). `enable_recording: 'raw-tracks'` é
 * ativado em toda sala criada aqui: cada participante gera um arquivo de
 * áudio próprio, o que resolve a identificação de locutor (Analista vs
 * Sujeito) sem depender de diarização por IA — o `userId` do meeting token
 * (ver criarTokenDeAcesso) é o mesmo que aparece em cada trilha.
 *
 * ATENÇÃO: o formato exato do JSON de GET /recordings (campos de trilha
 * por participante) deve ser conferido contra a documentação atual do
 * Daily.co antes de operar com credenciais reais — mapearGravacao() isola
 * essa leitura para ser fácil de ajustar sem tocar no resto do sistema.
 */
final class DailyCoVideoConferenceService implements VideoConferenceInterface
{
    private const ENDPOINT = 'https://api.daily.co/v1';

    public function __construct(
        private readonly ?string $apiKey = null,
        private readonly ?string $dominio = null
    ) {
    }

    public function criarSala(string $nomeSala): SalaCriadaDTO
    {
        $resposta = $this->chamar('POST', '/rooms', [
            'name' => $nomeSala,
            'properties' => [
                'enable_recording' => 'raw-tracks',
            ],
        ]);

        return new SalaCriadaDTO(
            nomeSala: (string) ($resposta['name'] ?? $nomeSala),
            url: (string) ($resposta['url'] ?? $this->urlDaSala($nomeSala))
        );
    }

    public function criarTokenDeAcesso(string $nomeSala, string $userId, string $userName, bool $ehProprietario): string
    {
        $resposta = $this->chamar('POST', '/meeting-tokens', [
            'properties' => [
                'room_name' => $nomeSala,
                'user_id' => $userId,
                'user_name' => $userName,
                'is_owner' => $ehProprietario,
            ],
        ]);

        $token = $resposta['token'] ?? null;

        if (!is_string($token) || $token === '') {
            throw new RuntimeException('O provedor de videochamada não devolveu um token de acesso válido.');
        }

        return $token;
    }

    public function encerrarSala(string $nomeSala): void
    {
        $this->chamar('DELETE', '/rooms/' . rawurlencode($nomeSala));
    }

    /**
     * @return RecordingDTO[]
     */
    public function buscarGravacoesFinalizadas(string $nomeSala): array
    {
        $resposta = $this->chamar('GET', '/recordings?room_name=' . rawurlencode($nomeSala));

        $gravacoes = [];

        foreach ($resposta['data'] ?? [] as $gravacao) {
            if (!is_array($gravacao) || ($gravacao['status'] ?? null) !== 'finished') {
                continue;
            }

            $gravacoes[] = $this->mapearGravacao($gravacao);
        }

        return $gravacoes;
    }

    /**
     * @param array<string, mixed> $gravacao
     */
    private function mapearGravacao(array $gravacao): RecordingDTO
    {
        $tracks = [];

        foreach ($gravacao['tracks'] ?? [] as $track) {
            if (!is_array($track)) {
                continue;
            }

            $tracks[] = new TrackDTO(
                userId: (string) ($track['user_id'] ?? ''),
                downloadUrl: (string) ($track['download_url'] ?? ''),
                offsetInicioSegundos: (float) ($track['start_ts'] ?? 0)
            );
        }

        return new RecordingDTO(
            recordingId: (string) ($gravacao['id'] ?? ''),
            status: (string) ($gravacao['status'] ?? ''),
            tracks: $tracks
        );
    }

    public function baixarTrilha(TrackDTO $track): string
    {
        $handle = curl_init($track->downloadUrl);

        curl_setopt_array($handle, [
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $bytes = curl_exec($handle);
        $erroCurl = curl_errno($handle);
        $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        curl_close($handle);

        if ($erroCurl !== 0 || $bytes === false || $status < 200 || $status >= 300) {
            throw new RuntimeException('Falha ao baixar a trilha de áudio da videochamada.');
        }

        return $bytes;
    }

    private function urlDaSala(string $nomeSala): string
    {
        $dominio = $this->dominio ?? (getenv('DAILY_DOMAIN') ?: '');

        return sprintf('https://%s.daily.co/%s', $dominio, $nomeSala);
    }

    /**
     * @param array<string, mixed> $corpo
     * @return array<string, mixed>
     */
    private function chamar(string $metodo, string $caminho, array $corpo = []): array
    {
        $chave = $this->apiKey ?? (getenv('DAILY_API_KEY') ?: '');

        $handle = curl_init(self::ENDPOINT . $caminho);

        $opcoes = [
            CURLOPT_CUSTOMREQUEST => $metodo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $chave,
                'Content-Type: application/json',
            ],
        ];

        if ($metodo !== 'GET') {
            $opcoes[CURLOPT_POSTFIELDS] = json_encode($corpo, JSON_THROW_ON_ERROR);
        }

        curl_setopt_array($handle, $opcoes);

        $corpoResposta = curl_exec($handle);
        $erroCurl = curl_errno($handle);
        $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        curl_close($handle);

        if ($erroCurl !== 0 || $corpoResposta === false) {
            throw new RuntimeException('Falha de comunicação com o provedor de videochamada.');
        }

        if ($corpoResposta === '') {
            return [];
        }

        $decodificado = json_decode((string) $corpoResposta, true);

        if ($status < 200 || $status >= 300 || !is_array($decodificado)) {
            throw new RuntimeException('O provedor de videochamada devolveu uma resposta inválida.');
        }

        /** @var array<string, mixed> $decodificado */
        return $decodificado;
    }
}
