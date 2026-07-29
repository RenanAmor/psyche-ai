<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use PDO;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\Repositories\SujeitoRepository;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\NomeSujeito;

final class SQLiteSujeitoRepository implements SujeitoRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findById(string $id): ?Sujeito
    {
        $statement = $this->pdo->prepare('SELECT id, nome FROM sujeitos WHERE id = :id');
        $statement->execute(['id' => $id]);

        $linha = $statement->fetch();

        if ($linha === false) {
            return null;
        }

        return $this->hidratar($linha);
    }

    /**
     * @return Sujeito[]
     */
    public function findAll(): array
    {
        $statement = $this->pdo->query('SELECT id, nome FROM sujeitos ORDER BY rowid ASC');

        return array_map(
            fn (array $linha): Sujeito => $this->hidratar($linha),
            $statement->fetchAll()
        );
    }

    /**
     * @param array<string, mixed> $linha
     */
    private function hidratar(array $linha): Sujeito
    {
        $sujeito = new Sujeito(
            new Identificador((string) $linha['id']),
            new NomeSujeito((string) $linha['nome'])
        );

        foreach (SessaoMapper::findBySujeitoId($this->pdo, $sujeito->id()->valor()) as $sessao) {
            $sujeito->adicionarSessao($sessao);
        }

        return $sujeito;
    }

    public function save(Sujeito $sujeito): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO sujeitos (id, nome)
             VALUES (:id, :nome)
             ON CONFLICT(id) DO UPDATE SET nome = excluded.nome'
        );
        $statement->execute([
            'id' => $sujeito->id()->valor(),
            'nome' => $sujeito->nome()->valor(),
        ]);

        foreach ($sujeito->sessoes() as $sessao) {
            SessaoMapper::save($this->pdo, $sessao, $sujeito->id()->valor());
        }
    }

    public function remove(Sujeito $sujeito): void
    {
        $statement = $this->pdo->prepare('DELETE FROM sujeitos WHERE id = :id');
        $statement->execute(['id' => $sujeito->id()->valor()]);
    }
}
