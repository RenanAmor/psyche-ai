<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\NomeSujeito;

final class SujeitoTest extends TestCase
{
    public function testExposesIdAndNome(): void
    {
        $sujeito = new Sujeito(new Identificador('1'), new NomeSujeito('Sujeito Um'));

        $this->assertSame('1', $sujeito->id()->valor());
        $this->assertSame('Sujeito Um', $sujeito->nome()->valor());
    }

    public function testAcumulaSessoes(): void
    {
        $sujeito = new Sujeito(new Identificador('1'), new NomeSujeito('Sujeito Um'));
        $sessao = new Sessao(new Identificador('s1'), new DataSessao(new \DateTimeImmutable()));

        $sujeito->adicionarSessao($sessao);

        $this->assertSame([$sessao], $sujeito->sessoes());
    }
}
