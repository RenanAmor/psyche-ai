<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\ChamadaSessao;

final class ChamadaSessaoDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $sessaoId,
        public readonly string $salaUrl,
        public readonly string $status,
        public readonly string $criadaEm,
        public readonly string $expiraEm,
        public readonly ?string $encerradaEm
    ) {
    }

    public static function fromEntity(ChamadaSessao $chamada): self
    {
        return new self(
            id: $chamada->id()->valor(),
            sessaoId: $chamada->sessaoId(),
            salaUrl: $chamada->salaUrl(),
            status: $chamada->status()->value,
            criadaEm: $chamada->criadaEm()->format('Y-m-d H:i:s'),
            expiraEm: $chamada->expiraEm()->format('Y-m-d H:i:s'),
            encerradaEm: $chamada->encerradaEm()?->format('Y-m-d H:i:s')
        );
    }
}
