<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Contracts;

interface RepositoryInterface
{
    public function save(object $entity): void;

    public function remove(object $entity): void;

    public function findById(string $id): ?object;
}