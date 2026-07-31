<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarGravacaoAudio;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Domain\Entities\GravacaoAudio;

final class RegistrarGravacaoAudioResult implements ResultInterface
{
    public function __construct(
        private readonly GravacaoAudio $gravacao
    ) {
    }

    public function gravacao(): GravacaoAudio
    {
        return $this->gravacao;
    }
}
