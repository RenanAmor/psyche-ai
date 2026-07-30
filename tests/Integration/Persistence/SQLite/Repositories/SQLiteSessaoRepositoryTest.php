<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\NomeSujeito;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSessaoRepository;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class SQLiteSessaoRepositoryTest extends SQLiteTestCase
{
    public function testSalvaEEncontraPorIdComDiscursos(): void
    {
        $sessao = new Sessao(new Identificador('sessao-1'), new DataSessao(new DateTimeImmutable('2026-02-01 09:00:00')));
        $sessao->adicionarDiscurso(new Discurso(new Identificador('discurso-1'), new ConteudoDiscursivo('Fala inicial')));

        $repositorio = new SQLiteSessaoRepository($this->pdo);
        $repositorio->save($sessao);

        $sessaoRecuperada = $repositorio->findById('sessao-1');

        self::assertNotNull($sessaoRecuperada);
        self::assertEquals(
            $sessao->data()->valor()->format('Y-m-d H:i:s'),
            $sessaoRecuperada->data()->valor()->format('Y-m-d H:i:s')
        );
        self::assertCount(1, $sessaoRecuperada->discursos());
        self::assertSame('Fala inicial', $sessaoRecuperada->discursos()[0]->conteudo()->valor());
    }

    public function testFindByIdRetornaNuloQuandoNaoEncontrado(): void
    {
        $repositorio = new SQLiteSessaoRepository($this->pdo);

        self::assertNull($repositorio->findById('inexistente'));
    }

    public function testSalvarNovamenteAtualizaASessaoSemPerderVinculoComOSujeito(): void
    {
        $sujeito = new Sujeito(new Identificador('sujeito-1'), new NomeSujeito('Sujeito Um'));
        $sessao = new Sessao(new Identificador('sessao-1'), new DataSessao(new DateTimeImmutable('2026-02-01 09:00:00')));
        $sujeito->adicionarSessao($sessao);

        (new SQLiteSujeitoRepository($this->pdo))->save($sujeito);

        $sessaoAtualizada = new Sessao(new Identificador('sessao-1'), new DataSessao(new DateTimeImmutable('2026-03-15 14:30:00')));
        (new SQLiteSessaoRepository($this->pdo))->save($sessaoAtualizada);

        $statement = $this->pdo->prepare('SELECT sujeito_id, data FROM sessoes WHERE id = :id');
        $statement->execute(['id' => 'sessao-1']);
        $linha = $statement->fetch();

        self::assertSame('sujeito-1', $linha['sujeito_id']);
        self::assertSame('2026-03-15 14:30:00', $linha['data']);
    }

    public function testRemoverExcluiASessaoECascateiaParaOsDiscursos(): void
    {
        $sessao = new Sessao(new Identificador('sessao-1'), new DataSessao(new DateTimeImmutable('2026-02-01 09:00:00')));
        $sessao->adicionarDiscurso(new Discurso(new Identificador('discurso-1'), new ConteudoDiscursivo('Fala inicial')));

        $repositorio = new SQLiteSessaoRepository($this->pdo);
        $repositorio->save($sessao);

        $repositorio->remove($sessao);

        self::assertNull($repositorio->findById('sessao-1'));
        self::assertSame(0, (int) $this->pdo->query('SELECT COUNT(*) FROM discursos')->fetchColumn());
    }

    public function testSujeitoIdDaSessaoDevolveOIdDoSujeitoVinculado(): void
    {
        $sujeito = new Sujeito(new Identificador('sujeito-1'), new NomeSujeito('Sujeito Um'));
        $sujeito->adicionarSessao(
            new Sessao(new Identificador('sessao-1'), new DataSessao(new DateTimeImmutable('2026-02-01 09:00:00')))
        );

        (new SQLiteSujeitoRepository($this->pdo))->save($sujeito);

        $repositorio = new SQLiteSessaoRepository($this->pdo);

        self::assertSame('sujeito-1', $repositorio->sujeitoIdDaSessao('sessao-1'));
    }

    public function testSujeitoIdDaSessaoDevolveNuloQuandoASessaoNaoTemSujeitoOuNaoExiste(): void
    {
        $repositorio = new SQLiteSessaoRepository($this->pdo);
        $repositorio->save(new Sessao(new Identificador('sessao-avulsa'), new DataSessao(new DateTimeImmutable('2026-02-01 09:00:00'))));

        self::assertNull($repositorio->sujeitoIdDaSessao('sessao-avulsa'));
        self::assertNull($repositorio->sujeitoIdDaSessao('inexistente'));
    }
}
