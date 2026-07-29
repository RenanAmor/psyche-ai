<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

final class Recorrencia extends Entity
{
    public function __construct(
        string $id,
        private readonly string $descricao,
        private int $frequencia = 1
    ) {
        parent::__construct($id);
    }

    public function descricao(): string
    {
        return $this->descricao;
    }

    public function frequencia(): int
    {
        return $this->frequencia;
    }

    public function incrementar(): void
    {
        $this->frequencia++;
    }
}