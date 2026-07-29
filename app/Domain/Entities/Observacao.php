<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\ObservacaoTexto;

final class Observacao extends Entity
{
    public function __construct(
        Identificador $id,
        private readonly ObservacaoTexto $texto
    ) {
        parent::__construct($id);
    }

    public function texto(): ObservacaoTexto
    {
        return $this->texto;
    }
}