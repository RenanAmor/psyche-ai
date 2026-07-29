<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use PDO;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;

/**
 * Mapeamento interno entre Discurso e a tabela discursos, cascateando
 * seus EventoDiscursivo. Usado por SQLiteDiscursoRepository e pelos
 * repositórios de agregados superiores (Sessao, Sujeito).
 */
final class DiscursoMapper
{
    public static function save(PDO $pdo, Discurso $discurso, ?string $sessaoId): void
    {
        if ($sessaoId !== null) {
            $statement = $pdo->prepare(
                'INSERT INTO discursos (id, sessao_id, conteudo)
                 VALUES (:id, :sessao_id, :conteudo)
                 ON CONFLICT(id) DO UPDATE SET
                    sessao_id = excluded.sessao_id,
                    conteudo = excluded.conteudo'
            );
            $statement->execute([
                'id' => $discurso->id()->valor(),
                'sessao_id' => $sessaoId,
                'conteudo' => $discurso->conteudo()->valor(),
            ]);
        } else {
            $statement = $pdo->prepare(
                'INSERT INTO discursos (id, sessao_id, conteudo)
                 VALUES (:id, NULL, :conteudo)
                 ON CONFLICT(id) DO UPDATE SET
                    conteudo = excluded.conteudo'
            );
            $statement->execute([
                'id' => $discurso->id()->valor(),
                'conteudo' => $discurso->conteudo()->valor(),
            ]);
        }

        $deleteEventosStatement = $pdo->prepare(
            'DELETE FROM eventos_discursivos WHERE discurso_id = :discurso_id'
        );
        $deleteEventosStatement->execute(['discurso_id' => $discurso->id()->valor()]);

        foreach ($discurso->eventos() as $evento) {
            EventoDiscursivoMapper::save($pdo, $evento, $discurso->id()->valor());
        }
    }

    public static function findById(PDO $pdo, string $id): ?Discurso
    {
        $statement = $pdo->prepare('SELECT id, conteudo FROM discursos WHERE id = :id');
        $statement->execute(['id' => $id]);

        $linha = $statement->fetch();

        if ($linha === false) {
            return null;
        }

        return self::hidratar($pdo, $linha);
    }

    /**
     * @return Discurso[]
     */
    public static function findAll(PDO $pdo): array
    {
        $statement = $pdo->query('SELECT id, conteudo FROM discursos ORDER BY rowid ASC');

        return array_map(
            fn (array $linha): Discurso => self::hidratar($pdo, $linha),
            $statement->fetchAll()
        );
    }

    /**
     * @return Discurso[]
     */
    public static function findBySessaoId(PDO $pdo, string $sessaoId): array
    {
        $statement = $pdo->prepare(
            'SELECT id, conteudo FROM discursos WHERE sessao_id = :sessao_id ORDER BY rowid ASC'
        );
        $statement->execute(['sessao_id' => $sessaoId]);

        return array_map(
            fn (array $linha): Discurso => self::hidratar($pdo, $linha),
            $statement->fetchAll()
        );
    }

    /**
     * @param array<string, mixed> $linha
     */
    private static function hidratar(PDO $pdo, array $linha): Discurso
    {
        $discurso = new Discurso(
            new Identificador((string) $linha['id']),
            new ConteudoDiscursivo((string) $linha['conteudo'])
        );

        foreach (EventoDiscursivoMapper::findByDiscursoId($pdo, $discurso->id()->valor()) as $evento) {
            $discurso->adicionarEvento($evento);
        }

        return $discurso;
    }
}
