<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\NomeSujeito;

final class EntityTest extends TestCase
{
    public function testEntitiesWithSameIdAreEqual(): void
    {
        $entityA = new Sujeito(new Identificador('1'), new NomeSujeito('Sujeito Um'));
        $entityB = new Sujeito(new Identificador('1'), new NomeSujeito('Outro Nome'));

        $this->assertTrue($entityA->equals($entityB));
    }

    public function testEntitiesWithDifferentIdsAreNotEqual(): void
    {
        $entityA = new Sujeito(new Identificador('1'), new NomeSujeito('Sujeito Um'));
        $entityB = new Sujeito(new Identificador('2'), new NomeSujeito('Sujeito Um'));

        $this->assertFalse($entityA->equals($entityB));
    }

    public function testEntitiesOfDifferentTypesAreNeverEqualEvenWithSameId(): void
    {
        $sujeito = new Sujeito(new Identificador('1'), new NomeSujeito('Sujeito Um'));
        $sessao = new Sessao(new Identificador('1'), new DataSessao(new \DateTimeImmutable()));

        $this->assertFalse($sujeito->equals($sessao));
    }
}