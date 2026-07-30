<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use PDO;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Repositories\SessaoRepository;

final class SQLiteSessaoRepository implements SessaoRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findById(string $id): ?Sessao
    {
        return SessaoMapper::findById($this->pdo, $id);
    }

    /**
     * @return Sessao[]
     */
    public function findAll(): array
    {
        return SessaoMapper::findAll($this->pdo);
    }

    public function save(Sessao $sessao): void
    {
        SessaoMapper::save($this->pdo, $sessao, null);
    }

    public function remove(Sessao $sessao): void
    {
        $statement = $this->pdo->prepare('DELETE FROM sessoes WHERE id = :id');
        $statement->execute(['id' => $sessao->id()->valor()]);
    }

    public function sujeitoIdDaSessao(string $sessaoId): ?string
    {
        return SessaoMapper::sujeitoIdDe($this->pdo, $sessaoId);
    }
}
