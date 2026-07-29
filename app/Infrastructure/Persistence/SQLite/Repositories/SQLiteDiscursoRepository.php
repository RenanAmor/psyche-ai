<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use PDO;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Repositories\DiscursoRepository;

final class SQLiteDiscursoRepository implements DiscursoRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findById(string $id): ?Discurso
    {
        return DiscursoMapper::findById($this->pdo, $id);
    }

    /**
     * @return Discurso[]
     */
    public function findAll(): array
    {
        return DiscursoMapper::findAll($this->pdo);
    }

    public function save(Discurso $discurso): void
    {
        DiscursoMapper::save($this->pdo, $discurso, null);
    }

    public function remove(Discurso $discurso): void
    {
        $statement = $this->pdo->prepare('DELETE FROM discursos WHERE id = :id');
        $statement->execute(['id' => $discurso->id()->valor()]);
    }

    public function sessaoIdDoDiscurso(string $discursoId): ?string
    {
        return DiscursoMapper::sessaoIdDe($this->pdo, $discursoId);
    }
}
