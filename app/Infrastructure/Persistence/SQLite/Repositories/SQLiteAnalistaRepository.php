<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PDO;
use PsycheAI\Domain\Entities\Analista;
use PsycheAI\Domain\Repositories\AnalistaRepository;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;

final class SQLiteAnalistaRepository implements AnalistaRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findById(string $id): ?Analista
    {
        $statement = $this->pdo->prepare(
            'SELECT id, email, senha_hash, criado_em FROM analistas WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        $linha = $statement->fetch();

        return $linha === false ? null : $this->hidratar($linha);
    }

    public function findByEmail(string $email): ?Analista
    {
        $statement = $this->pdo->prepare(
            'SELECT id, email, senha_hash, criado_em FROM analistas WHERE email = :email'
        );
        $statement->execute(['email' => $email]);

        $linha = $statement->fetch();

        return $linha === false ? null : $this->hidratar($linha);
    }

    public function save(Analista $analista): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO analistas (id, email, senha_hash, criado_em)
             VALUES (:id, :email, :senha_hash, :criado_em)
             ON CONFLICT(id) DO UPDATE SET
                email = excluded.email,
                senha_hash = excluded.senha_hash'
        );
        $statement->execute([
            'id' => $analista->id()->valor(),
            'email' => $analista->email()->valor(),
            'senha_hash' => $analista->senhaHash(),
            'criado_em' => $analista->criadoEm()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @param array<string, mixed> $linha
     */
    private function hidratar(array $linha): Analista
    {
        return new Analista(
            new Identificador((string) $linha['id']),
            new Email((string) $linha['email']),
            (string) $linha['senha_hash'],
            new DateTimeImmutable((string) $linha['criado_em'])
        );
    }
}
