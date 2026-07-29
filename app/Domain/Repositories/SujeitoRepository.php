<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Repositories;

use PsycheAI\Domain\Contracts\RepositoryInterface;
use PsycheAI\Domain\Entities\Sujeito;

interface SujeitoRepository extends RepositoryInterface
{
    public function findById(string $id): ?Sujeito;

    public function save(Sujeito $sujeito): void;

    public function remove(Sujeito $sujeito): void;
}