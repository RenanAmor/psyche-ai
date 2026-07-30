<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\ConsolidacaoMemoriaDTO;

final class ConsolidacaoResponse
{
    private function __construct(
        private readonly ConsolidacaoMemoriaDTO $dto
    ) {
    }

    public static function fromDTO(ConsolidacaoMemoriaDTO $dto): self
    {
        return new self($dto);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'sujeitoId' => $this->dto->sujeitoId,
            'quantidadeDeSessoes' => $this->dto->quantidadeDeSessoes,
            'quantidadeDeDiscursos' => $this->dto->quantidadeDeDiscursos,
            'quantidadeDeEventosDiscursivos' => $this->dto->quantidadeDeEventosDiscursivos,
            'quantidadeDeMemorias' => $this->dto->quantidadeDeMemorias,
        ];
    }
}
