<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\TranscreverGravacaoAudio;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Entities\GravacaoAudio;

final class TranscreverGravacaoAudioResult implements ResultInterface
{
    /**
     * @param EventoDiscursivo[] $eventos
     */
    public function __construct(
        private readonly array $eventos,
        private readonly GravacaoAudio $gravacao
    ) {
    }

    /**
     * @return EventoDiscursivo[]
     */
    public function eventos(): array
    {
        return $this->eventos;
    }

    public function gravacao(): GravacaoAudio
    {
        return $this->gravacao;
    }
}
