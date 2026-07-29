<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite\Repositories;

use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteDiscursoRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class SQLiteDiscursoRepositoryTest extends SQLiteTestCase
{
    public function testSalvaEEncontraPorIdComEventos(): void
    {
        $discurso = new Discurso(new Identificador('discurso-1'), new ConteudoDiscursivo('Conteúdo'));
        $discurso->adicionarEvento(new EventoDiscursivo(
            new Identificador('evento-1'),
            new ConteudoDiscursivo('Lapso'),
            new Posicao(2)
        ));

        $repositorio = new SQLiteDiscursoRepository($this->pdo);
        $repositorio->save($discurso);

        $discursoRecuperado = $repositorio->findById('discurso-1');

        self::assertNotNull($discursoRecuperado);
        self::assertSame('Conteúdo', $discursoRecuperado->conteudo()->valor());
        self::assertCount(1, $discursoRecuperado->eventos());
        self::assertSame('Lapso', $discursoRecuperado->eventos()[0]->conteudo()->valor());
        self::assertSame(2, $discursoRecuperado->eventos()[0]->posicao()->valor());
    }

    public function testFindByIdRetornaNuloQuandoNaoEncontrado(): void
    {
        $repositorio = new SQLiteDiscursoRepository($this->pdo);

        self::assertNull($repositorio->findById('inexistente'));
    }

    public function testSalvarNovamenteAtualizaOConteudo(): void
    {
        $repositorio = new SQLiteDiscursoRepository($this->pdo);
        $repositorio->save(new Discurso(new Identificador('discurso-1'), new ConteudoDiscursivo('Original')));

        $repositorio->save(new Discurso(new Identificador('discurso-1'), new ConteudoDiscursivo('Atualizado')));

        self::assertSame('Atualizado', $repositorio->findById('discurso-1')->conteudo()->valor());
        self::assertSame(1, (int) $this->pdo->query('SELECT COUNT(*) FROM discursos')->fetchColumn());
    }

    public function testRemoverExcluiODiscursoECascateiaParaOsEventos(): void
    {
        $discurso = new Discurso(new Identificador('discurso-1'), new ConteudoDiscursivo('Conteúdo'));
        $discurso->adicionarEvento(new EventoDiscursivo(
            new Identificador('evento-1'),
            new ConteudoDiscursivo('Lapso'),
            new Posicao(1)
        ));

        $repositorio = new SQLiteDiscursoRepository($this->pdo);
        $repositorio->save($discurso);

        $repositorio->remove($discurso);

        self::assertNull($repositorio->findById('discurso-1'));
        self::assertSame(0, (int) $this->pdo->query('SELECT COUNT(*) FROM eventos_discursivos')->fetchColumn());
    }
}
