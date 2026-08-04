<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PDO;
use PsycheAI\Domain\Entities\ChamadaSessao;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\StatusChamada;

final class ChamadaSessaoMapper
{
    public static function save(PDO $pdo, ChamadaSessao $chamada): void
    {
        $statement = $pdo->prepare(
            'INSERT INTO chamadas_sessao (id, sessao_id, sala_provedor_id, sala_url, token_acesso, status, criada_em, expira_em, encerrada_em, processada_em)
             VALUES (:id, :sessao_id, :sala_provedor_id, :sala_url, :token_acesso, :status, :criada_em, :expira_em, :encerrada_em, :processada_em)
             ON CONFLICT(id) DO UPDATE SET
                sala_provedor_id = excluded.sala_provedor_id,
                sala_url = excluded.sala_url,
                token_acesso = excluded.token_acesso,
                status = excluded.status,
                expira_em = excluded.expira_em,
                encerrada_em = excluded.encerrada_em,
                processada_em = excluded.processada_em'
        );
        $statement->execute([
            'id' => $chamada->id()->valor(),
            'sessao_id' => $chamada->sessaoId(),
            'sala_provedor_id' => $chamada->salaProvedorId(),
            'sala_url' => $chamada->salaUrl(),
            'token_acesso' => $chamada->tokenAcesso(),
            'status' => $chamada->status()->value,
            'criada_em' => $chamada->criadaEm()->format('Y-m-d H:i:s'),
            'expira_em' => $chamada->expiraEm()->format('Y-m-d H:i:s'),
            'encerrada_em' => $chamada->encerradaEm()?->format('Y-m-d H:i:s'),
            'processada_em' => $chamada->processadaEm()?->format('Y-m-d H:i:s'),
        ]);
    }

    public static function findById(PDO $pdo, string $id): ?ChamadaSessao
    {
        $statement = $pdo->prepare(self::selecao() . ' WHERE id = :id');
        $statement->execute(['id' => $id]);

        $linha = $statement->fetch();

        return $linha === false ? null : self::hidratar($linha);
    }

    public static function findBySessaoId(PDO $pdo, string $sessaoId): ?ChamadaSessao
    {
        $statement = $pdo->prepare(self::selecao() . ' WHERE sessao_id = :sessao_id');
        $statement->execute(['sessao_id' => $sessaoId]);

        $linha = $statement->fetch();

        return $linha === false ? null : self::hidratar($linha);
    }

    public static function findByTokenAcesso(PDO $pdo, string $tokenAcesso): ?ChamadaSessao
    {
        $statement = $pdo->prepare(self::selecao() . ' WHERE token_acesso = :token_acesso');
        $statement->execute(['token_acesso' => $tokenAcesso]);

        $linha = $statement->fetch();

        return $linha === false ? null : self::hidratar($linha);
    }

    /**
     * @return ChamadaSessao[]
     */
    public static function findEncerradasNaoProcessadas(PDO $pdo): array
    {
        $statement = $pdo->query(
            self::selecao() . " WHERE status = 'encerrada' AND processada_em IS NULL ORDER BY rowid ASC"
        );

        return array_map(self::hidratar(...), $statement->fetchAll());
    }

    private static function selecao(): string
    {
        return 'SELECT id, sessao_id, sala_provedor_id, sala_url, token_acesso, status, criada_em, expira_em, encerrada_em, processada_em
                 FROM chamadas_sessao';
    }

    /**
     * @param array<string, mixed> $linha
     */
    private static function hidratar(array $linha): ChamadaSessao
    {
        return new ChamadaSessao(
            new Identificador((string) $linha['id']),
            (string) $linha['sessao_id'],
            (string) $linha['sala_provedor_id'],
            (string) $linha['sala_url'],
            (string) $linha['token_acesso'],
            new DateTimeImmutable((string) $linha['criada_em']),
            new DateTimeImmutable((string) $linha['expira_em']),
            StatusChamada::from((string) $linha['status']),
            $linha['encerrada_em'] === null ? null : new DateTimeImmutable((string) $linha['encerrada_em']),
            $linha['processada_em'] === null ? null : new DateTimeImmutable((string) $linha['processada_em'])
        );
    }
}
