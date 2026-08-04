<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PDO;
use PsycheAI\Domain\Entities\AnotacaoSessao;
use PsycheAI\Domain\ValueObjects\ConteudoAnotacao;
use PsycheAI\Domain\ValueObjects\Identificador;

final class AnotacaoSessaoMapper
{
    public static function save(PDO $pdo, AnotacaoSessao $anotacao): void
    {
        $statement = $pdo->prepare(
            'INSERT INTO anotacoes_sessao (id, sessao_id, conteudo, atualizado_em)
             VALUES (:id, :sessao_id, :conteudo, :atualizado_em)
             ON CONFLICT(sessao_id) DO UPDATE SET
                conteudo = excluded.conteudo,
                atualizado_em = excluded.atualizado_em'
        );
        $statement->execute([
            'id' => $anotacao->id()->valor(),
            'sessao_id' => $anotacao->sessaoId(),
            'conteudo' => $anotacao->conteudo()->valor(),
            'atualizado_em' => $anotacao->atualizadoEm()->format('Y-m-d H:i:s'),
        ]);
    }

    public static function findBySessaoId(PDO $pdo, string $sessaoId): ?AnotacaoSessao
    {
        $statement = $pdo->prepare(
            'SELECT id, sessao_id, conteudo, atualizado_em FROM anotacoes_sessao WHERE sessao_id = :sessao_id'
        );
        $statement->execute(['sessao_id' => $sessaoId]);

        $linha = $statement->fetch();

        if ($linha === false) {
            return null;
        }

        return new AnotacaoSessao(
            new Identificador((string) $linha['id']),
            (string) $linha['sessao_id'],
            new ConteudoAnotacao((string) $linha['conteudo']),
            new DateTimeImmutable((string) $linha['atualizado_em'])
        );
    }
}
