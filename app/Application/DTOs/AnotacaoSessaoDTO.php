<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\AnotacaoSessao;

final class AnotacaoSessaoDTO
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $sessaoId,
        public readonly string $conteudo,
        public readonly ?string $atualizadoEm
    ) {
    }

    public static function fromEntity(AnotacaoSessao $anotacao): self
    {
        return new self(
            id: $anotacao->id()->valor(),
            sessaoId: $anotacao->sessaoId(),
            conteudo: $anotacao->conteudo()->valor(),
            atualizadoEm: $anotacao->atualizadoEm()->format('Y-m-d H:i:s')
        );
    }

    public static function vazia(string $sessaoId): self
    {
        return new self(id: null, sessaoId: $sessaoId, conteudo: '', atualizadoEm: null);
    }
}
