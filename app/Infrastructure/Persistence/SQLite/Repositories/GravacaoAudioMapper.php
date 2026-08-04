<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PDO;
use PsycheAI\Domain\Entities\GravacaoAudio;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Locutor;
use PsycheAI\Domain\ValueObjects\StatusTranscricao;

final class GravacaoAudioMapper
{
    public static function save(PDO $pdo, GravacaoAudio $gravacao): void
    {
        $statement = $pdo->prepare(
            'INSERT INTO gravacoes_audio (id, sessao_id, caminho_armazenamento, status, criado_em, transcrita_em, locutor)
             VALUES (:id, :sessao_id, :caminho_armazenamento, :status, :criado_em, :transcrita_em, :locutor)
             ON CONFLICT(id) DO UPDATE SET
                sessao_id = excluded.sessao_id,
                caminho_armazenamento = excluded.caminho_armazenamento,
                status = excluded.status,
                transcrita_em = excluded.transcrita_em,
                locutor = excluded.locutor'
        );
        $statement->execute([
            'id' => $gravacao->id()->valor(),
            'sessao_id' => $gravacao->sessaoId(),
            'caminho_armazenamento' => $gravacao->caminhoArmazenamento(),
            'status' => $gravacao->status()->value,
            'criado_em' => $gravacao->criadaEm()->format('Y-m-d H:i:s'),
            'transcrita_em' => $gravacao->transcritaEm()?->format('Y-m-d H:i:s'),
            'locutor' => $gravacao->locutor()->value,
        ]);
    }

    public static function findById(PDO $pdo, string $id): ?GravacaoAudio
    {
        $statement = $pdo->prepare(
            'SELECT id, sessao_id, caminho_armazenamento, status, criado_em, transcrita_em, locutor
             FROM gravacoes_audio WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        $linha = $statement->fetch();

        return $linha === false ? null : self::hidratar($linha);
    }

    /**
     * @return GravacaoAudio[]
     */
    public static function findBySessaoId(PDO $pdo, string $sessaoId): array
    {
        $statement = $pdo->prepare(
            'SELECT id, sessao_id, caminho_armazenamento, status, criado_em, transcrita_em, locutor
             FROM gravacoes_audio WHERE sessao_id = :sessao_id ORDER BY rowid ASC'
        );
        $statement->execute(['sessao_id' => $sessaoId]);

        return array_map(self::hidratar(...), $statement->fetchAll());
    }

    /**
     * @return GravacaoAudio[]
     */
    public static function findPendentesDeTranscricao(PDO $pdo): array
    {
        $statement = $pdo->prepare(
            'SELECT id, sessao_id, caminho_armazenamento, status, criado_em, transcrita_em, locutor
             FROM gravacoes_audio WHERE status = :status ORDER BY rowid ASC'
        );
        $statement->execute(['status' => StatusTranscricao::Pendente->value]);

        return array_map(self::hidratar(...), $statement->fetchAll());
    }

    /**
     * @param array<string, mixed> $linha
     */
    private static function hidratar(array $linha): GravacaoAudio
    {
        return new GravacaoAudio(
            new Identificador((string) $linha['id']),
            (string) $linha['sessao_id'],
            (string) $linha['caminho_armazenamento'],
            StatusTranscricao::from((string) $linha['status']),
            new DateTimeImmutable((string) $linha['criado_em']),
            $linha['transcrita_em'] === null ? null : new DateTimeImmutable((string) $linha['transcrita_em']),
            Locutor::from((string) $linha['locutor'])
        );
    }
}
