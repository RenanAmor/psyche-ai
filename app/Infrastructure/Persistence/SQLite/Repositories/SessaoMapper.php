<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PDO;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;

/**
 * Mapeamento interno entre Sessao e a tabela sessoes, cascateando seus
 * Discurso. Usado por SQLiteSessaoRepository e pelos repositórios de
 * agregados relacionados (Sujeito, Memoria).
 */
final class SessaoMapper
{
    public static function save(PDO $pdo, Sessao $sessao, ?string $sujeitoId): void
    {
        if ($sujeitoId !== null) {
            $statement = $pdo->prepare(
                'INSERT INTO sessoes (id, sujeito_id, data)
                 VALUES (:id, :sujeito_id, :data)
                 ON CONFLICT(id) DO UPDATE SET
                    sujeito_id = excluded.sujeito_id,
                    data = excluded.data'
            );
            $statement->execute([
                'id' => $sessao->id()->valor(),
                'sujeito_id' => $sujeitoId,
                'data' => $sessao->data()->valor()->format('Y-m-d H:i:s'),
            ]);
        } else {
            $statement = $pdo->prepare(
                'INSERT INTO sessoes (id, sujeito_id, data)
                 VALUES (:id, NULL, :data)
                 ON CONFLICT(id) DO UPDATE SET
                    data = excluded.data'
            );
            $statement->execute([
                'id' => $sessao->id()->valor(),
                'data' => $sessao->data()->valor()->format('Y-m-d H:i:s'),
            ]);
        }

        foreach ($sessao->discursos() as $discurso) {
            DiscursoMapper::save($pdo, $discurso, $sessao->id()->valor());
        }
    }

    public static function findById(PDO $pdo, string $id): ?Sessao
    {
        $statement = $pdo->prepare('SELECT id, data FROM sessoes WHERE id = :id');
        $statement->execute(['id' => $id]);

        $linha = $statement->fetch();

        if ($linha === false) {
            return null;
        }

        return self::hidratar($pdo, $linha);
    }

    /**
     * @return Sessao[]
     */
    public static function findAll(PDO $pdo): array
    {
        $statement = $pdo->query('SELECT id, data FROM sessoes ORDER BY data ASC');

        return array_map(
            fn (array $linha): Sessao => self::hidratar($pdo, $linha),
            $statement->fetchAll()
        );
    }

    /**
     * @return Sessao[]
     */
    public static function findBySujeitoId(PDO $pdo, string $sujeitoId): array
    {
        $statement = $pdo->prepare(
            'SELECT id, data FROM sessoes WHERE sujeito_id = :sujeito_id ORDER BY data ASC'
        );
        $statement->execute(['sujeito_id' => $sujeitoId]);

        return array_map(
            fn (array $linha): Sessao => self::hidratar($pdo, $linha),
            $statement->fetchAll()
        );
    }

    /**
     * @param array<string, mixed> $linha
     */
    private static function hidratar(PDO $pdo, array $linha): Sessao
    {
        $sessao = new Sessao(
            new Identificador((string) $linha['id']),
            new DataSessao(new DateTimeImmutable((string) $linha['data']))
        );

        foreach (DiscursoMapper::findBySessaoId($pdo, $sessao->id()->valor()) as $discurso) {
            $sessao->adicionarDiscurso($discurso);
        }

        return $sessao;
    }
}
