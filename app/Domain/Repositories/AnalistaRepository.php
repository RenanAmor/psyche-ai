<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Repositories;

use PsycheAI\Domain\Contracts\RepositoryInterface;
use PsycheAI\Domain\Entities\Analista;

interface AnalistaRepository extends RepositoryInterface
{
    public function findById(string $id): ?Analista;

    public function findByEmail(string $email): ?Analista;

    public function save(Analista $analista): void;
}
