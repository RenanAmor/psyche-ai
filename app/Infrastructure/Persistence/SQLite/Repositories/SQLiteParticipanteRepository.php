<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PDO;
use PsycheAI\Domain\Entities\Participante;
use PsycheAI\Domain\Repositories\ParticipanteRepository;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;

final class SQLiteParticipanteRepository implements ParticipanteRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findById(string $id): ?Participante
    {
        $statement = $this->pdo->prepare(
            'SELECT id, email, senha_hash, criado_em FROM participantes WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        $linha = $statement->fetch();

        return $linha === false ? null : $this->hidratar($linha);
    }

    public function findByEmail(string $email): ?Participante
    {
        $statement = $this->pdo->prepare(
            'SELECT id, email, senha_hash, criado_em FROM participantes WHERE email = :email'
        );
        $statement->execute(['email' => $email]);

        $linha = $statement->fetch();

        return $linha === false ? null : $this->hidratar($linha);
    }

    public function save(Participante $participante): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO participantes (id, email, senha_hash, criado_em)
             VALUES (:id, :email, :senha_hash, :criado_em)
             ON CONFLICT(id) DO UPDATE SET
                email = excluded.email,
                senha_hash = excluded.senha_hash'
        );
        $statement->execute([
            'id' => $participante->id()->valor(),
            'email' => $participante->email()->valor(),
            'senha_hash' => $participante->senhaHash(),
            'criado_em' => $participante->criadoEm()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @param array<string, mixed> $linha
     */
    private function hidratar(array $linha): Participante
    {
        return new Participante(
            new Identificador((string) $linha['id']),
            new Email((string) $linha['email']),
            (string) $linha['senha_hash'],
            new DateTimeImmutable((string) $linha['criado_em'])
        );
    }
}
