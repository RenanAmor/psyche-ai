<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;

final class Discurso extends Entity
{
    /**
     * @var EventoDiscursivo[]
     */
    private array $eventos = [];

    public function __construct(
        Identificador $id,
        private readonly ConteudoDiscursivo $conteudo
    ) {
        parent::__construct($id);
    }

    public function conteudo(): ConteudoDiscursivo
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