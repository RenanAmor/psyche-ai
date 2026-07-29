<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Application\UseCases\RegistrarSessao\RegistrarSessaoCommand;
use PsycheAI\Application\UseCases\RegistrarSessao\RegistrarSessaoHandler;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\NomeSujeito;

final class RegistrarSessaoHandlerTest extends TestCase
{
    public function testRegistraSessaoEAssociaAoSujeito(): void
    {
        $sujeito = new Sujeito(new Identificador('1'), new NomeSujeito('Sujeito Um'));
        $data = new DateTimeImmutable('2026-01-10 10:00:00');

        $handler = new RegistrarSessaoHandler();
        $result = $handler->handle(new RegistrarSessaoCommand($sujeito, 's1', $data));

        $this->assertSame([$result->sessao()], $sujeito->sessoes());
        $this->assertSame('s1', $result->dto()->id);
        $this->assertSame(0, $result->dto()->quantidadeDeDiscursos);
    }

    public function testLancaComandoInvalidoQuandoIdVazio(): void
    {
        $sujeito = new Sujeito(new Identificador('1'), new NomeSujeito('Sujeito Um'));
        $handler = new RegistrarSessaoHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new RegistrarSessaoCommand($sujeito, '', new DateTimeImmutable()));
    }
}
