<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\EventoDiscursivo;

final class EventoDiscursivoDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $conteudo,
        public readonly int $posicao,
        public readonly string $discursoId = '',
        public readonly ?string $sessaoId = null,
        public readonly string $criadoEm = ''
    ) {
    }

    public static function fromEntity(EventoDiscursivo $evento, string $discursoId = '', ?string $sessaoId = null): self
    {
        return new self(
            id: $evento->id()->valor(),
            conteudo: $evento->conteudo()->valor(),
            posicao: $evento->posicao()->valor(),
            discursoId: $discursoId,
            sessaoId: $sessaoId,
            criadoEm: $evento->criadoEm()->format('Y-m-d H:i:s')
        );
    }
}
