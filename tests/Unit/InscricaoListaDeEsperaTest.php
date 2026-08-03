<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\InscricaoListaDeEspera;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;

final class InscricaoListaDeEsperaTest extends TestCase
{
    private function criarInscricao(): InscricaoListaDeEspera
    {
        return new InscricaoListaDeEspera(
            new Identificador('1'),
            new Email('interessado@psyche.ai'),
            'Ana Interessada',
            'Psicóloga',
            'Universidade Federal',
            'Brasil/SP',
            'Quero participar da pesquisa sobre discurso computacional.',
            true,
            true,
            new DateTimeImmutable('2026-08-01 10:00:00')
        );
    }

    public function testExposesTodosOsCampos(): void
    {
        $inscricao = $this->criarInscricao();

        $this->assertSame('1', $inscricao->id()->valor());
        $this->assertSame('interessado@psyche.ai', $inscricao->email()->valor());
        $this->assertSame('Ana Interessada', $inscricao->nome());
        $this->assertSame('Psicóloga', $inscricao->profissao());
        $this->assertSame('Universidade Federal', $inscricao->instituicao());
        $this->assertSame('Brasil/SP', $inscricao->paisEstado());
        $this->assertSame('Quero participar da pesquisa sobre discurso computacional.', $inscricao->motivoInteresse());
        $this->assertTrue($inscricao->aceitouPoliticaPrivacidade());
        $this->assertTrue($inscricao->aceitouTermoConsentimento());
        $this->assertSame('2026-08-01 10:00:00', $inscricao->criadoEm()->format('Y-m-d H:i:s'));
    }

    public function testProfissaoEInstituicaoSaoOpcionais(): void
    {
        $inscricao = new InscricaoListaDeEspera(
            new Identificador('1'),
            new Email('interessado@psyche.ai'),
            'Ana Interessada',
            null,
            null,
            'Brasil/SP',
            'Interesse pessoal.',
            true,
            true,
            new DateTimeImmutable()
        );

        $this->assertNull($inscricao->profissao());
        $this->assertNull($inscricao->instituicao());
    }
}
