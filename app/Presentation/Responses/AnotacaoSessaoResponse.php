<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\AnotacaoSessaoDTO;

final class AnotacaoSessaoResponse
{
    private function __construct(
        private readonly AnotacaoSessaoDTO $dto
    ) {
    }

    public static function fromDTO(AnotacaoSessaoDTO $dto): self
    {
        return new self($dto);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->dto->id,
            'sessaoId' => $this->dto->sessaoId,
            'conteudo' => $this->dto->conteudo,
            'atualizadoEm' => $this->dto->atualizadoEm,
        ];
    }
}
