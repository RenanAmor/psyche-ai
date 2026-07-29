<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

final class Discurso extends Entity
{
    /**
     * @var EventoDiscursivo[]
     */
    private array $eventos = [];

    public function __construct(
        string $id,
        private readonly string $conteudo
    ) {
        parent::__construct($id);
    }

    public function conteudo(): string
    {
        return $this->conteudo;
    }

    public function adicionarEvento(EventoDiscursivo $evento): void
    {
        $this->eventos[] = $evento;
    }

    /**
     * @return EventoDiscursivo[]
     */
    public function eventos(): array
    {
        return $this->eventos;
    }
}