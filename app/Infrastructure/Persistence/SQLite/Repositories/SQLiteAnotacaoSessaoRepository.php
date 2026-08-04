<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use PDO;
use PsycheAI\Domain\Entities\AnotacaoSessao;
use PsycheAI\Domain\Repositories\AnotacaoSessaoRepository;

final class SQLiteAnotacaoSessaoRepository implements AnotacaoSessaoRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findBySessaoId(string $sessaoId): ?AnotacaoSessao
    {
        return AnotacaoSessaoMapper::findBySessaoId($this->pdo, $sessaoId);
    }

    public function save(AnotacaoSessao $anotacao): void
    {
        AnotacaoSessaoMapper::save($this->pdo, $anotacao);
    }
}
