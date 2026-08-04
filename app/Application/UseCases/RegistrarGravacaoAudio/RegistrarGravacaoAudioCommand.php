<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarGravacaoAudio;

use PsycheAI\Application\Contracts\CommandInterface;
use PsycheAI\Domain\ValueObjects\Locutor;

final class RegistrarGravacaoAudioCommand implements CommandInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $sessaoId,
        public readonly string $caminhoArmazenamento,
        public readonly Locutor $locutor = Locutor::Sujeito
    ) {
    }
}
