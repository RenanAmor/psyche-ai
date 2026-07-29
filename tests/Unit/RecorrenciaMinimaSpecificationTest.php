<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Recorrencia;
use PsycheAI\Domain\Specifications\RecorrenciaMinimaSpecification;
use PsycheAI\Domain\ValueObjects\Frequencia;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Texto;

final class RecorrenciaMinimaSpecificationTest extends TestCase
{
    public function testNaoSatisfeitaAbaixoDoMinimo(): void
    {
        $recorrencia = new Recorrencia(new Identificador('r1'), new Texto('descrição'), new Frequencia(1));

        $this->assertFalse((new RecorrenciaMinimaSpecification())->isSatisfiedBy($recorrencia));
    }

    public function testSatisfeitaNoMinimo(): void
    {
        $recorrencia = new Recorrencia(new Identificador('r1'), new Texto('descrição'), new Frequencia(2));

        $this->assertTrue((new RecorrenciaMinimaSpecification())->isSatisfiedBy($recorrencia));
    }
}
