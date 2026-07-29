<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\NomeSujeito;

final class Sujeito extends Entity
{
    /**
     * @var Sessao[]
     */
    private array $sessoes = [];

    public function __construct(
        Identificador $id,
        private readonly NomeSujeito $nome
    ) {
        parent::__construct($id);
    }

    public function nome(): NomeSujeito
    {
        return $this->nome;
    }

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