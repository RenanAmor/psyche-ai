<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;

final class Sessao extends Entity
{
    /**
     * @var Discurso[]
     */
    private array $discursos = [];

    public function __construct(
        Identificador $id,
        private readonly DataSessao $data
    ) {
        parent::__construct($id);
    }

    public function data(): DataSessao
    {
        return $this->data;
    }

    public function adicionarDiscurso(Discurso $discurso): void
    {
        $this->discursos[] = $discurso;
    }

    /**
     * @return Discurso[]
     */
    public function discursos(): array
    {
        return $this->discursos;
    }
}