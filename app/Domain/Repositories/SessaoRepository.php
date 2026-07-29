<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Repositories;

use PsycheAI\Domain\Contracts\RepositoryInterface;
use PsycheAI\Domain\Entities\Sessao;

interface SessaoRepository extends RepositoryInterface
{
    public function findById(string $id): ?Sessao;

    /**
     * @return Sessao[]
     */
    public function findAll(): array;

    public function save(Sessao $sessao): void;

    public function remove(Sessao $sessao): void;
}