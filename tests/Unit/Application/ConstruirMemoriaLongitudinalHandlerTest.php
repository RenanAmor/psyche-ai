<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal\ConstruirMemoriaLongitudinalCommand;
use PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal\ConstruirMemoriaLongitudinalHandler;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\NomeSujeito;

final class ConstruirMemoriaLongitudinalHandlerTest extends TestCase
{
    public function testConstroiMemoriaOrdenadaCronologicamente(): void
    {
        $sujeito = new Sujeito(new Identificador('1'), new NomeSujeito('Sujeito Um'));

        $sessaoRecente = new Sessao(new Identificador('s2'), new DataSessao(new DateTimeImmutable('2026-02-01')));
        $sessaoAntiga = new Sessao(new Identificador('s1'), new DataSessao(new DateTimeImmutable('2026-01-01')));

        $sujeito->adicionarSessao($sessaoRecente);
        $sujeito->adicionarSessao($sessaoAntiga);

        $handler = new ConstruirMemoriaLongitudinalHandler();
        $result = $handler->handle(new ConstruirMemoriaLongitudinalCommand($sujeito, 'mem1'));

        $memoria = $result->memoria();
        $this->assertSame('mem1', $memoria->id()->valor());
        $this->assertSame(2, $memoria->quantidadeDeSessoes());
        $this->assertSame([$sessaoAntiga, $sessaoRecente], $memoria->sessoes());

        $dto = $result->dto();
        $this->assertSame(2, $dto->quantidadeDeSessoes);
        $this->assertSame('s1', $dto->sessoes[0]->id);
        $this->assertSame('s2', $dto->sessoes[1]->id);
    }
}
