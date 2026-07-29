<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

final class MemoriaLongitudinal extends Entity
{
    /**
     * @var Sessao[]
     */
    private array $sessoes = [];

    public function adicionarSessao(Sessao $sessao): void
    {
        $this->sessoes[] = $sessao;
    }

    /**
     * @return Sessao[]
     */
    public function sessoes(): array
    {
        return $this->sessoes;
    }

    public function quantidadeDeSessoes(): int
    {
        return count($this->sessoes);
    }
}