<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PsycheAI\Domain\Entities\Participante;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteParticipanteRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class SQLiteParticipanteRepositoryTest extends SQLiteTestCase
{
    private function criarParticipante(string $id = 'participante-1', string $email = 'participante@psyche.ai'): Participante
    {
        return new Participante(
            new Identificador($id),
            new Email($email),
            password_hash('segredo', PASSWORD_DEFAULT),
            new DateTimeImmutable('2026-07-30 10:00:00')
        );
    }

    public function testSalvaEEncontraPorId(): void
    {
        $repositorio = new SQLiteParticipanteRepository($this->pdo);
        $repositorio->save($this->criarParticipante());

        $recuperado = $repositorio->findById('participante-1');

        $this->assertNotNull($recuperado);
        $this->assertSame('participante@psyche.ai', $recuperado->email()->valor());
        $this->assertTrue($recuperado->verificarSenha('segredo'));
    }

    public function testFindByIdRetornaNuloQuandoNaoEncontrado(): void
    {
        $repositorio = new SQLiteParticipanteRepository($this->pdo);

        $this->assertNull($repositorio->findById('inexistente'));
    }

    public function testEncontraPorEmail(): void
    {
        $repositorio = new SQLiteParticipanteRepository($this->pdo);
        $repositorio->save($this->criarParticipante());

        $recuperado = $repositorio->findByEmail('participante@psyche.ai');

        $this->assertNotNull($recuperado);
        $this->assertSame('participante-1', $recuperado->id()->valor());
    }

    public function testFindByEmailRetornaNuloQuandoNaoEncontrado(): void
    {
        $repositorio = new SQLiteParticipanteRepository($this->pdo);

        $this->assertNull($repositorio->findByEmail('inexistente@psyche.ai'));
    }

    public function testSalvarNovamenteAtualizaOParticipante(): void
    {
        $repositorio = new SQLiteParticipanteRepository($this->pdo);
        $repositorio->save($this->criarParticipante());

        $repositorio->save(new Participante(
            new Identificador('participante-1'),
            new Email('novo-email@psyche.ai'),
            password_hash('nova-senha', PASSWORD_DEFAULT),
            new DateTimeImmutable('2026-07-30 10:00:00')
        ));

        $recuperado = $repositorio->findById('participante-1');

        $this->assertSame('novo-email@psyche.ai', $recuperado->email()->valor());
        $this->assertTrue($recuperado->verificarSenha('nova-senha'));

        $statement = $this->pdo->query('SELECT COUNT(*) FROM participantes');
        $this->assertSame(1, (int) $statement->fetchColumn());
    }
}
