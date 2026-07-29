<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

final class EventoDiscursivo extends Entity
{
    public function __construct(
        string $id,
        private readonly string $conteudo,
        private readonly int $posicao
    ) {
        parent::__construct($id);
    }

    public function conteudo(): string
    {
        return $this->conteudo;
    }

    public function posicao(): int
    {
        return $this->posicao;
    }
}