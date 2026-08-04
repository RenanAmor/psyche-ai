<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

use PsycheAI\Infrastructure\Contracts\DTOs\RecordingDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\SalaCriadaDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\TrackDTO;

/**
 * Porta para o provedor de videochamada (Daily.co) — sala com gravação por
 * trilha separada por participante, para que a identificação de locutor
 * (Analista/Sujeito) venha de qual trilha é, não de diarização por IA.
 */
interface VideoConferenceInterface
{
    public function criarSala(string $nomeSala): SalaCriadaDTO;

    public function criarTokenDeAcesso(string $nomeSala, string $userId, string $userName, bool $ehProprietario): string;

    public function encerrarSala(string $nomeSala): void;

    /**
     * @return RecordingDTO[]
     */
    public function buscarGravacoesFinalizadas(string $nomeSala): array;

    public function baixarTrilha(TrackDTO $track): string;
}
