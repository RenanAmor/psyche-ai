<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use PDO;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;

/**
 * Mapeamento interno entre EventoDiscursivo e a tabela eventos_discursivos.
 * Usado pelos repositórios de Discurso, Sessao, Sujeito e Memoria para
 * salvar/carregar os eventos em cascata, sem expor uma porta própria de
 * Domínio (EventoDiscursivo não possui repositório dedicado).
 */
final class EventoDiscursivoMapper
{
    public static function save(PDO $pdo, EventoDiscursivo $evento, ?string $discursoId): void
    {
        if ($discursoId !== null) {
            $statement = $pdo->prepare(
                'INSERT INTO eventos_discursivos (id, discurso_id, conteudo, posicao)
                 VALUES (:id, :discurso_id, :conteudo, :posicao)
                 ON CONFLICT(id) DO UPDATE SET
                    discurso_id = excluded.discurso_id,
                    conteudo = excluded.conteudo,
                    posicao = excluded.posicao'
            );
            $statement->execute([
                'id' => $evento->id()->valor(),
                'discurso_id' => $discursoId,
                'conteudo' => $evento->conteudo()->valor(),
                'posicao' => $evento->posicao()->valor(),
            ]);

            return;
        }

        $statement = $pdo->prepare(
            'INSERT INTO eventos_discursivos (id, discurso_id, conteudo, posicao)
             VALUES (:id, NULL, :conteudo, :posicao)
             ON CONFLICT(id) DO UPDATE SET
                conteudo = excluded.conteudo,
                posicao = excluded.posicao'
        );
        $statement->execute([
            'id' => $evento->id()->valor(),
            'conteudo' => $evento->conteudo()->valor(),
            'posicao' => $evento->posicao()->valor(),
        ]);
    }

    public static function findById(PDO $pdo, string $id): ?EventoDiscursivo
    {
        $statement = $pdo->prepare(
            'SELECT id, conteudo, posicao FROM eventos_discursivos WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        $linha = $statement->fetch();

        if ($linha === false) {
            return null;
        }

        return self::hidratar($linha);
    }

    /**
     * @return EventoDiscursivo[]
     */
    public static function findByDiscursoId(PDO $pdo, string $discursoId): array
    {
        $statement = $pdo->prepare(
            'SELECT id, conteudo, posicao FROM eventos_discursivos
             WHERE discurso_id = :discurso_id
             ORDER BY posicao ASC'
        );
        $statement->execute(['discurso_id' => $discursoId]);

        return array_map(
            static fn (array $linha): EventoDiscursivo => self::hidratar($linha),
            $statement->fetchAll()
        );
    }

    /**
     * @param array<string, mixed> $linha
     */
    private static function hidratar(array $linha): EventoDiscursivo
    {
        return new EventoDiscursivo(
            new Identificador((string) $linha['id']),
            new ConteudoDiscursivo((string) $linha['conteudo']),
            new Posicao((int) $linha['posicao'])
        );
    }
}
