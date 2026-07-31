<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\SujeitoDTO;

/**
 * Projeta SujeitoDTO (Application) para o array que compõe o campo "data"
 * do envelope JSON da API — nunca expõe a Entidade de Domínio Sujeito.
 */
final class SujeitoResponse
{
    private function __construct(
        private readonly SujeitoDTO $dto
    ) {
    }

    public static function fromDTO(SujeitoDTO $dto): self
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
            'nome' => $this->dto->nome,
            'quantidadeDeSessoes' => $this->dto->quantidadeDeSessoes,
            'email' => $this->dto->email,
        ];
    }
}
