<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use PsycheAI\Domain\ValueObjects\Frequencia;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Texto;

final class Recorrencia extends Entity
{
    public function __construct(
        Identificador $id,
        private readonly Texto $descricao,
        private Frequencia $frequencia = new Frequencia(1)
    ) {
        parent::__construct($id);
    }

    public function descricao(): Texto
    {
        return $this->descricao;
    }

    public function frequencia(): Frequencia
    {
        return $this->frequencia;
    }

    public function incrementar(): void
    {
        $this->frequencia = $this->frequencia->incrementar();
    }
}