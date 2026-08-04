<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Support;

use PsycheAI\Infrastructure\Contracts\DTOs\RecordingDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\SalaCriadaDTO;
use PsycheAI\Infrastructure\Contracts\DTOs\TrackDTO;
use PsycheAI\Infrastructure\Contracts\VideoConferenceInterface;

/**
 * Duplo de teste de VideoConferenceInterface: sem chamada real ao Daily.co.
 * `gravacoesPorSala`/`bytesPorTrack` são configuráveis pelo teste antes de
 * chamar processarGravacoes(); `salasEncerradas` registra chamadas a
 * encerrarSala() para os testes verificarem que foi de fato acionada.
 */
final class FakeVideoConferenceService implements VideoConferenceInterface
{
    /** @var array<string, RecordingDTO[]> */
    public array $gravacoesPorSala = [];

    /** @var array<string, string> chave: userId da track */
    public array $bytesPorTrack = [];

    /** @var string[] */
    public array $salasEncerradas = [];

    public function criarSala(string $nomeSala): SalaCriadaDTO
    {
        return new SalaCriadaDTO($nomeSala, 'https://fake.daily.co/' . $nomeSala);
    }

    public function criarTokenDeAcesso(string $nomeSala, string $userId, string $userName, bool $ehProprietario): string
    {
        return 'token-' . $userId . '-' . $nomeSala;
    }

    public function encerrarSala(string $nomeSala): void
    {
        $this->salasEncerradas[] = $nomeSala;
    }

    /**
     * @return RecordingDTO[]
     */
    public function buscarGravacoesFinalizadas(string $nomeSala): array
    {
        return $this->gravacoesPorSala[$nomeSala] ?? [];
    }

    public function baixarTrilha(TrackDTO $track): string
    {
        return $this->bytesPorTrack[$track->userId] ?? '';
    }
}
