<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Integration\Persistence\SQLite\Repositories;

use DateTimeImmutable;
use PsycheAI\Domain\Entities\InscricaoListaDeEspera;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Infrastructure\Persistence\SQLite\Repositories\SQLiteListaDeEsperaRepository;
use PsycheAI\Tests\Integration\Persistence\SQLite\SQLiteTestCase;

final class SQLiteListaDeEsperaRepositoryTest extends SQLiteTestCase
{
    private function criarInscricao(
        string $id = 'inscricao-1',
        string $email = 'interessado@psyche.ai',
        string $criadoEm = '2026-08-01 10:00:00',
        ?string $profissao = 'Psicóloga',
        ?string $instituicao = 'Universidade Federal'
    ): InscricaoListaDeEspera {
        return new InscricaoListaDeEspera(
            new Identificador($id),
            new Email($email),
            'Ana Interessada',
            $profissao,
            $instituicao,
            'Brasil/SP',
            'Quero participar da pesquisa.',
            true,
            true,
            new DateTimeImmutable($criadoEm)
        );
    }

    public function testSalvaEEncontraPorEmail(): void
    {
        $repositorio = new SQLiteListaDeEsperaRepository($this->pdo);
        $repositorio->save($this->criarInscricao());

        $recuperado = $repositorio->findByEmail('interessado@psyche.ai');

        $this->assertNotNull($recuperado);
        $this->assertSame('inscricao-1', $recuperado->id()->valor());
        $this->assertSame('Ana Interessada', $recuperado->nome());
        $this->assertSame('Psicóloga', $recuperado->profissao());
        $this->assertSame('Universidade Federal', $recuperado->instituicao());
        $this->assertSame('Brasil/SP', $recuperado->paisEstado());
        $this->assertSame('Quero participar da pesquisa.', $recuperado->motivoInteresse());
        $this->assertTrue($recuperado->aceitouPoliticaPrivacidade());
        $this->assertTrue($recuperado->aceitouTermoConsentimento());
    }

    public function testSalvaComProfissaoEInstituicaoNulas(): void
    {
        $repositorio = new SQLiteListaDeEsperaRepository($this->pdo);
        $repositorio->save($this->criarInscricao(profissao: null, instituicao: null));

        $recuperado = $repositorio->findByEmail('interessado@psyche.ai');

        $this->assertNull($recuperado->profissao());
        $this->assertNull($recuperado->instituicao());
    }

    public function testFindByEmailRetornaNuloQuandoNaoEncontrado(): void
    {
        $repositorio = new SQLiteListaDeEsperaRepository($this->pdo);

        $this->assertNull($repositorio->findByEmail('inexistente@psyche.ai'));
    }

    public function testFindAllRetornaVazioPorPadrao(): void
    {
        $repositorio = new SQLiteListaDeEsperaRepository($this->pdo);

        $this->assertSame([], $repositorio->findAll());
    }

    public function testFindAllRetornaTodasAsInscricoesMaisRecentesPrimeiro(): void
    {
        $repositorio = new SQLiteListaDeEsperaRepository($this->pdo);
        $repositorio->save($this->criarInscricao('inscricao-1', 'primeiro@psyche.ai', '2026-08-01 10:00:00'));
        $repositorio->save($this->criarInscricao('inscricao-2', 'segundo@psyche.ai', '2026-08-02 10:00:00'));

        $todas = $repositorio->findAll();

        $this->assertCount(2, $todas);
        $this->assertSame('segundo@psyche.ai', $todas[0]->email()->valor());
        $this->assertSame('primeiro@psyche.ai', $todas[1]->email()->valor());
    }

    public function testEmailERestritoAUnico(): void
    {
        $repositorio = new SQLiteListaDeEsperaRepository($this->pdo);
        $repositorio->save($this->criarInscricao('inscricao-1', 'interessado@psyche.ai'));

        $this->expectException(\PDOException::class);

        $repositorio->save($this->criarInscricao('inscricao-2', 'interessado@psyche.ai'));
    }
}
