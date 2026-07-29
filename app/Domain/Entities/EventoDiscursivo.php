<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use DateTimeImmutable;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;

final class EventoDiscursivo extends Entity
{
    private readonly DateTimeImmutable $criadoEm;

    public function __construct(
        Identificador $id,
        private readonly ConteudoDiscursivo $conteudo,
        private readonly Posicao $posicao,
        ?DateTimeImmutable $criadoEm = null
    ) {
        parent::__construct($id);

        $this->criadoEm = $criadoEm ?? new DateTimeImmutable();
    }

    public function conteudo(): ConteudoDiscursivo
    {
        return $this->conteudo;
    }

    public function posicao(): Posicao
    {
        return $this->posicao;
    }

    public function criadoEm(): DateTimeImmutable
    {
        return $this->criadoEm;
    }
}