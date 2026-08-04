<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Repositories;

use PsycheAI\Domain\Contracts\RepositoryInterface;
use PsycheAI\Domain\Entities\ChamadaSessao;

interface ChamadaSessaoRepository extends RepositoryInterface
{
    public function findById(string $id): ?ChamadaSessao;

    public function findBySessaoId(string $sessaoId): ?ChamadaSessao;

    public function findByTokenAcesso(string $tokenAcesso): ?ChamadaSessao;

    /**
     * @return ChamadaSessao[]
     */
    public function findEncerradasNaoProcessadas(): array;

    public function save(ChamadaSessao $chamada): void;
}
