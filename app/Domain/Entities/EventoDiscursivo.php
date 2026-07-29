<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;

final class EventoDiscursivo extends Entity
{
    public function __construct(
        Identificador $id,
        private readonly ConteudoDiscursivo $conteudo,
        private readonly Posicao $posicao
    ) {
        parent::__construct($id);
    }

    public function conteudo(): ConteudoDiscursivo
    {
        return $this->conteudo;
    }

    public function posicao(): Posicao
    {
        return $this->posicao;
    }
}