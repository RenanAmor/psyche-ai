<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

final class Sujeito extends Entity
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
}