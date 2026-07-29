<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\NomeSujeito;
use PsycheAI\Domain\ValueObjects\Posicao;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteSujeitoRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class SQLiteSujeitoRepositoryTest extends SQLiteTestCase
{
    private function criarSujeitoComGrafoCompleto(): Sujeito
    {
        $sujeito = new Sujeito(new Identificador('sujeito-1'), new NomeSujeito('Sujeito Um'));

        $sessao = new Sessao(new Identificador('sessao-1'), new DataSessao(new DateTimeImmutable('2026-01-10 10:00:00')));
        $discurso = new Discurso(new Identificador('discurso-1'), new ConteudoDiscursivo('Conteúdo do discurso'));
        $evento = new EventoDiscursivo(
            new Identificador('evento-1'),
            new ConteudoDiscursivo('Lapso'),
            new Posicao(3)
        );

        $discurso->adicionarEvento($evento);
        $sessao->adicionarDiscurso($discurso);
        $sujeito->adicionarSessao($sessao);

        return $sujeito;
    }

    public function testSalvaEEncontraPorIdComGrafoCompleto(): void
    {
        $repositorio = new SQLiteSujeitoRepository($this->pdo);
        $repositorio->save($this->criarSujeitoComGrafoCompleto());

        $sujeitoRecuperado = $repositorio->findById('sujeito-1');

        self::assertNotNull($sujeitoRecuperado);
        self::assertSame('Sujeito Um', $sujeitoRecuperado->nome()->valor());
        self::assertCount(1, $sujeitoRecuperado->sessoes());

        $sessaoRecuperada = $sujeitoRecuperado->sessoes()[0];
        self::assertSame('sessao-1', $sessaoRecuperada->id()->valor());
        self::assertCount(1, $sessaoRecuperada->discursos());

        $discursoRecuperado = $sessaoRecuperada->discursos()[0];
        self::assertSame('Conteúdo do discurso', $discursoRecuperado->conteudo()->valor());
        self::assertCount(1, $discursoRecuperado->eventos());

        $eventoRecuperado = $discursoRecuperado->eventos()[0];
        self::assertSame('Lapso', $eventoRecuperado->conteudo()->valor());
        self::assertSame(3, $eventoRecuperado->posicao()->valor());
    }

    public function testFindByIdRetornaNuloQuandoNaoEncontrado(): void
    {
        $repositorio = new SQLiteSujeitoRepository($this->pdo);

        self::assertNull($repositorio->findById('inexistente'));
    }

    public function testSalvarNovamenteAtualizaOSujeito(): void
    {
        $repositorio = new SQLiteSujeitoRepository($this->pdo);
        $repositorio->save(new Sujeito(new Identificador('sujeito-1'), new NomeSujeito('Nome Original')));

        $repositorio->save(new Sujeito(new Identificador('sujeito-1'), new NomeSujeito('Nome Atualizado')));

        $sujeitoRecuperado = $repositorio->findById('sujeito-1');

        self::assertSame('Nome Atualizado', $sujeitoRecuperado->nome()->valor());

        $statement = $this->pdo->query('SELECT COUNT(*) FROM sujeitos');
        self::assertSame(1, (int) $statement->fetchColumn());
    }

    public function testRemoverExcluiOSujeitoECascateiaParaOsFilhos(): void
    {
        $repositorio = new SQLiteSujeitoRepository($this->pdo);
        $sujeito = $this->criarSujeitoComGrafoCompleto();
        $repositorio->save($sujeito);

        $repositorio->remove($sujeito);

        self::assertNull($repositorio->findById('sujeito-1'));

        self::assertSame(0, (int) $this->pdo->query('SELECT COUNT(*) FROM sessoes')->fetchColumn());
        self::assertSame(0, (int) $this->pdo->query('SELECT COUNT(*) FROM discursos')->fetchColumn());
        self::assertSame(0, (int) $this->pdo->query('SELECT COUNT(*) FROM eventos_discursivos')->fetchColumn());
    }
}
