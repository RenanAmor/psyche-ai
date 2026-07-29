<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Repositories;

use PsycheAI\Domain\Contracts\RepositoryInterface;
use PsycheAI\Domain\Entities\Discurso;

interface DiscursoRepository extends RepositoryInterface
{
    public function findById(string $id): ?Discurso;

    public function save(Discurso $discurso): void;

    public function remove(Discurso $discurso): void;
}