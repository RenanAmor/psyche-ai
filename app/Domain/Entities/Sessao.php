<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

final class Sessao extends Entity
{
    /**
     * @var Discurso[]
     */
    private array $discursos = [];

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