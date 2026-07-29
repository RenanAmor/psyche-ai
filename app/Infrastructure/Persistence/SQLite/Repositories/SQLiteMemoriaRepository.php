<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use PDO;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;
use PsycheAI\Domain\Repositories\MemoriaRepository;
use PsycheAI\Domain\ValueObjects\Identificador;

final class SQLiteMemoriaRepository implements MemoriaRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findById(string $id): ?MemoriaLongitudinal
    {
        $statement = $this->pdo->prepare('SELECT id FROM memorias_longitudinais WHERE id = :id');
        $statement->execute(['id' => $id]);

        $linha = $statement->fetch();

        if ($linha === false) {
            return null;
        }

        return $this->hidratar($linha);
    }

    /**
     * @return MemoriaLongitudinal[]
     */
    public function findAll(): array
    {
        $statement = $this->pdo->query('SELECT id FROM memorias_longitudinais ORDER BY rowid ASC');

        return array_map(
            fn (array $linha): MemoriaLongitudinal => $this->hidratar($linha),
            $statement->fetchAll()
        );
    }

    /**
     * @param array<string, mixed> $linha
     */
    private function hidratar(array $linha): MemoriaLongitudinal
    {
        $memoria = new MemoriaLongitudinal(new Identificador((string) $linha['id']));

        $pivotStatement = $this->pdo->prepare(
            'SELECT sessao_id FROM memoria_sessoes WHERE memoria_id = :memoria_id ORDER BY ordem ASC'
        );
        $pivotStatement->execute(['memoria_id' => $memoria->id()->valor()]);

        foreach ($pivotStatement->fetchAll() as $pivotLinha) {
            $sessao = SessaoMapper::findById($this->pdo, (string) $pivotLinha['sessao_id']);

            if ($sessao !== null) {
                $memoria->adicionarSessao($sessao);
            }
        }

        return $memoria;
    }

    public function save(MemoriaLongitudinal $memoria): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO memorias_longitudinais (id) VALUES (:id)
             ON CONFLICT(id) DO NOTHING'
        );
        $statement->execute(['id' => $memoria->id()->valor()]);

        $deleteStatement = $this->pdo->prepare(
            'DELETE FROM memoria_sessoes WHERE memoria_id = :memoria_id'
        );
        $deleteStatement->execute(['memoria_id' => $memoria->id()->valor()]);

        $insertStatement = $this->pdo->prepare(
            'INSERT INTO memoria_sessoes (memoria_id, sessao_id, ordem)
             VALUES (:memoria_id, :sessao_id, :ordem)'
        );

        foreach (array_values($memoria->sessoes()) as $ordem => $sessao) {
            SessaoMapper::save($this->pdo, $sessao, null);

            $insertStatement->execute([
                'memoria_id' => $memoria->id()->valor(),
                'sessao_id' => $sessao->id()->valor(),
                'ordem' => $ordem,
            ]);
        }
    }

    public function remove(MemoriaLongitudinal $memoria): void
    {
        $statement = $this->pdo->prepare('DELETE FROM memorias_longitudinais WHERE id = :id');
        $statement->execute(['id' => $memoria->id()->valor()]);
    }
}
