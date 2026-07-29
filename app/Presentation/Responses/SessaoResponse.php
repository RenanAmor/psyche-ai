<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\SessaoDTO;

/**
 * Projeta SessaoDTO (Application) para o array que compõe o campo "data"
 * do envelope JSON da API — nunca expõe a Entidade de Domínio Sessao.
 */
final class SessaoResponse
{
    private function __construct(
        private readonly SessaoDTO $dto
    ) {
    }

    public static function fromDTO(SessaoDTO $dto): self
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
            'data' => $this->dto->data,
            'quantidadeDeDiscursos' => $this->dto->quantidadeDeDiscursos,
        ];
    }
}
