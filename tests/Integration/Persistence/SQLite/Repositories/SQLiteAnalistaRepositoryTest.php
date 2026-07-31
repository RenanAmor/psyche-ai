<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PsycheAI\Domain\Entities\Analista;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteAnalistaRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class SQLiteAnalistaRepositoryTest extends SQLiteTestCase
{
    private function criarAnalista(string $id = 'analista-1', string $email = 'analista@psyche.ai'): Analista
    {
        return new Analista(
            new Identificador($id),
            new Email($email),
            password_hash('segredo', PASSWORD_DEFAULT),
            new DateTimeImmutable('2026-07-30 10:00:00')
        );
    }

    public function testSalvaEEncontraPorId(): void
    {
        $repositorio = new SQLiteAnalistaRepository($this->pdo);
        $repositorio->save($this->criarAnalista());

        $recuperado = $repositorio->findById('analista-1');

        $this->assertNotNull($recuperado);
        $this->assertSame('analista@psyche.ai', $recuperado->email()->valor());
        $this->assertTrue($recuperado->verificarSenha('segredo'));
    }

    public function testFindByIdRetornaNuloQuandoNaoEncontrado(): void
    {
        $repositorio = new SQLiteAnalistaRepository($this->pdo);

        $this->assertNull($repositorio->findById('inexistente'));
    }

    public function testEncontraPorEmail(): void
    {
        $repositorio = new SQLiteAnalistaRepository($this->pdo);
        $repositorio->save($this->criarAnalista());

        $recuperado = $repositorio->findByEmail('analista@psyche.ai');

        $this->assertNotNull($recuperado);
        $this->assertSame('analista-1', $recuperado->id()->valor());
    }

    public function testFindByEmailRetornaNuloQuandoNaoEncontrado(): void
    {
        $repositorio = new SQLiteAnalistaRepository($this->pdo);

        $this->assertNull($repositorio->findByEmail('inexistente@psyche.ai'));
    }

    public function testSalvarNovamenteAtualizaOAnalista(): void
    {
        $repositorio = new SQLiteAnalistaRepository($this->pdo);
        $repositorio->save($this->criarAnalista());

        $repositorio->save(new Analista(
            new Identificador('analista-1'),
            new Email('novo-email@psyche.ai'),
            password_hash('nova-senha', PASSWORD_DEFAULT),
            new DateTimeImmutable('2026-07-30 10:00:00')
        ));

        $recuperado = $repositorio->findById('analista-1');

        $this->assertSame('novo-email@psyche.ai', $recuperado->email()->valor());
        $this->assertTrue($recuperado->verificarSenha('nova-senha'));

        $statement = $this->pdo->query('SELECT COUNT(*) FROM analistas');
        $this->assertSame(1, (int) $statement->fetchColumn());
    }
}
