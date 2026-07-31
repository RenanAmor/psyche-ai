<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\GravacaoAudio;

final class GravacaoAudioDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $sessaoId,
        public readonly string $status,
        public readonly string $criadaEm,
        public readonly ?string $transcritaEm
    ) {
    }

    public static function fromEntity(GravacaoAudio $gravacao): self
    {
        return new self(
            id: $gravacao->id()->valor(),
            sessaoId: $gravacao->sessaoId(),
            status: $gravacao->status()->value,
            criadaEm: $gravacao->criadaEm()->format('Y-m-d H:i:s'),
            transcritaEm: $gravacao->transcritaEm()?->format('Y-m-d H:i:s')
        );
    }
}
