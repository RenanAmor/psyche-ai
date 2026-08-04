<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts\DTOs;

/**
 * Uma trilha de áudio individual de uma gravação "raw-tracks" — uma por
 * participante da chamada. `userId` é o identificador que
 * ChamadaDeVideoApplicationService atribuiu ao mintar o meeting token do
 * participante ('analista' ou 'sujeito:{id}'), não um id gerado pelo Daily
 * — é o que permite atribuir o Locutor correto sem diarização por IA.
 */
final class TrackDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $downloadUrl,
        public readonly float $offsetInicioSegundos
    ) {
    }
}
