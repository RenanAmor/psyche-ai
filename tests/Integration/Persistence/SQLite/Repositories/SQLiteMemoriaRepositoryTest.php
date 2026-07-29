<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteMemoriaRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class SQLiteMemoriaRepositoryTest extends SQLiteTestCase
{
    public function testSalvaEEncontraPorIdPreservandoAOrdemDasSessoes(): void
    {
        $memoria = new MemoriaLongitudinal(new Identificador('memoria-1'));
        $memoria->adicionarSessao(new Sessao(new Identificador('sessao-1'), new DataSessao(new DateTimeImmutable('2026-01-01 10:00:00'))));
        $memoria->adicionarSessao(new Sessao(new Identificador('sessao-2'), new DataSessao(new DateTimeImmutable('2026-01-02 10:00:00'))));

        $repositorio = new SQLiteMemoriaRepository($this->pdo);
        $repositorio->save($memoria);

        $memoriaRecuperada = $repositorio->findById('memoria-1');

        self::assertNotNull($memoriaRecuperada);
        self::assertSame(2, $memoriaRecuperada->quantidadeDeSessoes());
        self::assertSame('sessao-1', $memoriaRecuperada->sessoes()[0]->id()->valor());
        self::assertSame('sessao-2', $memoriaRecuperada->sessoes()[1]->id()->valor());
    }

    public function testFindByIdRetornaNuloQuandoNaoEncontrado(): void
    {
        $repositorio = new SQLiteMemoriaRepository($this->pdo);

        self::assertNull($repositorio->findById('inexistente'));
    }

    public function testSalvarNovamenteSubstituiAAssociacaoDeSessoes(): void
    {
        $repositorio = new SQLiteMemoriaRepository($this->pdo);

        $memoria = new MemoriaLongitudinal(new Identificador('memoria-1'));
        $memoria->adicionarSessao(new Sessao(new Identificador('sessao-1'), new DataSessao(new DateTimeImmutable('2026-01-01 10:00:00'))));
        $repositorio->save($memoria);

        $memoriaAtualizada = new MemoriaLongitudinal(new Identificador('memoria-1'));
        $memoriaAtualizada->adicionarSessao(new Sessao(new Identificador('sessao-2'), new DataSessao(new DateTimeImmutable('2026-01-02 10:00:00'))));
        $repositorio->save($memoriaAtualizada);

        $memoriaRecuperada = $repositorio->findById('memoria-1');

        self::assertSame(1, $memoriaRecuperada->quantidadeDeSessoes());
        self::assertSame('sessao-2', $memoriaRecuperada->sessoes()[0]->id()->valor());
    }

    public function testRemoverExcluiAMemoriaSemRemoverAsSessoes(): void
    {
        $repositorio = new SQLiteMemoriaRepository($this->pdo);

        $memoria = new MemoriaLongitudinal(new Identificador('memoria-1'));
        $memoria->adicionarSessao(new Sessao(new Identificador('sessao-1'), new DataSessao(new DateTimeImmutable('2026-01-01 10:00:00'))));
        $repositorio->save($memoria);

        $repositorio->remove($memoria);

        self::assertNull($repositorio->findById('memoria-1'));
        self::assertSame(1, (int) $this->pdo->query('SELECT COUNT(*) FROM sessoes')->fetchColumn());
        self::assertSame(0, (int) $this->pdo->query('SELECT COUNT(*) FROM memoria_sessoes')->fetchColumn());
    }
}
