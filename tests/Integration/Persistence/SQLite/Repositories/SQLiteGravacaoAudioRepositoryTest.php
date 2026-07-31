<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite\Repositories;

use PsycheAI\Domain\Entities\GravacaoAudio;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteGravacaoAudioRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class SQLiteGravacaoAudioRepositoryTest extends SQLiteTestCase
{
    private function criarSessao(string $id): void
    {
        $this->pdo->prepare('INSERT INTO sessoes (id, data) VALUES (:id, :data)')
            ->execute(['id' => $id, 'data' => '2026-07-31 10:00:00']);
    }

    public function testSalvaEEncontraPorId(): void
    {
        $this->criarSessao('sessao-1');

        $repositorio = new SQLiteGravacaoAudioRepository($this->pdo);
        $repositorio->save(new GravacaoAudio(new Identificador('g1'), 'sessao-1', 'sessoes/sessao-1.webm'));

        $gravacao = $repositorio->findById('g1');

        self::assertNotNull($gravacao);
        self::assertSame('sessao-1', $gravacao->sessaoId());
        self::assertSame('sessoes/sessao-1.webm', $gravacao->caminhoArmazenamento());
    }

    public function testFindByIdRetornaNuloQuandoNaoEncontrado(): void
    {
        $repositorio = new SQLiteGravacaoAudioRepository($this->pdo);

        self::assertNull($repositorio->findById('inexistente'));
    }

    public function testFindBySessaoIdRetornaSoDaquelaSessao(): void
    {
        $this->criarSessao('sessao-1');
        $this->criarSessao('sessao-2');

        $repositorio = new SQLiteGravacaoAudioRepository($this->pdo);
        $repositorio->save(new GravacaoAudio(new Identificador('g1'), 'sessao-1', 'a.webm'));
        $repositorio->save(new GravacaoAudio(new Identificador('g2'), 'sessao-2', 'b.webm'));

        $gravacoes = $repositorio->findBySessaoId('sessao-1');

        self::assertCount(1, $gravacoes);
        self::assertSame('g1', $gravacoes[0]->id()->valor());
    }

    public function testFindPendentesDeTranscricaoIgnoraJaTranscritas(): void
    {
        $this->criarSessao('sessao-1');

        $pendente = new GravacaoAudio(new Identificador('g1'), 'sessao-1', 'a.webm');
        $transcrita = new GravacaoAudio(new Identificador('g2'), 'sessao-1', 'b.webm');
        $transcrita->marcarTranscrita();

        $repositorio = new SQLiteGravacaoAudioRepository($this->pdo);
        $repositorio->save($pendente);
        $repositorio->save($transcrita);

        $pendentes = $repositorio->findPendentesDeTranscricao();

        self::assertCount(1, $pendentes);
        self::assertSame('g1', $pendentes[0]->id()->valor());
    }

    public function testSalvarNovamenteAtualizaOStatus(): void
    {
        $this->criarSessao('sessao-1');

        $repositorio = new SQLiteGravacaoAudioRepository($this->pdo);
        $gravacao = new GravacaoAudio(new Identificador('g1'), 'sessao-1', 'a.webm');
        $repositorio->save($gravacao);

        $gravacao->marcarTranscrita();
        $repositorio->save($gravacao);

        $recuperada = $repositorio->findById('g1');
        self::assertNotNull($recuperada->transcritaEm());
        self::assertSame(1, (int) $this->pdo->query('SELECT COUNT(*) FROM gravacoes_audio')->fetchColumn());
    }
}
