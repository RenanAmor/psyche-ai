<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use PDO;
use PsycheAI\Domain\Entities\ChamadaSessao;
use PsycheAI\Domain\Repositories\ChamadaSessaoRepository;

final class SQLiteChamadaSessaoRepository implements ChamadaSessaoRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findById(string $id): ?ChamadaSessao
    {
        return ChamadaSessaoMapper::findById($this->pdo, $id);
    }

    public function findBySessaoId(string $sessaoId): ?ChamadaSessao
    {
        return ChamadaSessaoMapper::findBySessaoId($this->pdo, $sessaoId);
    }

    public function findByTokenAcesso(string $tokenAcesso): ?ChamadaSessao
    {
        return ChamadaSessaoMapper::findByTokenAcesso($this->pdo, $tokenAcesso);
    }

    /**
     * @return ChamadaSessao[]
     */
    public function findEncerradasNaoProcessadas(): array
    {
        return ChamadaSessaoMapper::findEncerradasNaoProcessadas($this->pdo);
    }

    public function save(ChamadaSessao $chamada): void
    {
        ChamadaSessaoMapper::save($this->pdo, $chamada);
    }
}
