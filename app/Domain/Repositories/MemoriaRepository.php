<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Repositories;

use PsycheAI\Domain\Contracts\RepositoryInterface;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;

interface MemoriaRepository extends RepositoryInterface
{
    public function findById(string $id): ?MemoriaLongitudinal;

    /**
     * @return MemoriaLongitudinal[]
     */
    public function findAll(): array;

    public function save(MemoriaLongitudinal $memoria): void;

    public function remove(MemoriaLongitudinal $memoria): void;
}