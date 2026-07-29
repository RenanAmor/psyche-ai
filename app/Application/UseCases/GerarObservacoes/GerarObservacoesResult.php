<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\GerarObservacoes;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Application\DTOs\ObservacaoDTO;
use PsycheAI\Domain\Entities\Observacao;

final class GerarObservacoesResult implements ResultInterface
{
    /**
     * @param Observacao[] $observacoes
     */
    public function __construct(
        private readonly array $observacoes
    ) {
    }

    /**
     * @return Observacao[]
     */
    public function observacoes(): array
    {
        return $this->observacoes;
    }

    /**
     * @return ObservacaoDTO[]
     */
    public function dtos(): array
    {
        return array_map(
            static fn (Observacao $observacao) => ObservacaoDTO::fromEntity($observacao),
            $this->observacoes
        );
    }
}
