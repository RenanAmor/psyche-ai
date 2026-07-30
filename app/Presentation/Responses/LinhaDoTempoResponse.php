<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\LinhaDoTempoItemDTO;
use PsycheAI\Application\DTOs\LinhaDoTempoResultadoDTO;

/**
 * Projeta LinhaDoTempoResultadoDTO (Application) para o array que compõe
 * o campo "data" do envelope JSON da API.
 */
final class LinhaDoTempoResponse
{
    private function __construct(
        private readonly LinhaDoTempoResultadoDTO $dto
    ) {
    }

    public static function fromDTO(LinhaDoTempoResultadoDTO $dto): self
    {
        return new self($dto);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'itens' => array_map(
                static fn (LinhaDoTempoItemDTO $item): array => [
                    'tipo' => $item->tipo,
                    'id' => $item->id,
                    'timestamp' => $item->timestamp,
                    'dados' => $item->dados,
                ],
                $this->dto->itens
            ),
            'pagina' => $this->dto->pagina,
            'porPagina' => $this->dto->porPagina,
            'total' => $this->dto->total,
            'totalDePaginas' => $this->dto->totalDePaginas,
        ];
    }
}
