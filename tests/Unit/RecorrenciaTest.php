<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Recorrencia;
use PsycheAI\Domain\ValueObjects\Frequencia;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Texto;

final class RecorrenciaTest extends TestCase
{
    public function testDefaultFrequenciaIsUm(): void
    {
        $recorrencia = new Recorrencia(new Identificador('r1'), new Texto('descrição'));

        $this->assertSame(1, $recorrencia->frequencia()->valor());
    }

    public function testIncrementarAumentaAFrequencia(): void
    {
        $recorrencia = new Recorrencia(
            new Identificador('r1'),
            new Texto('descrição'),
            new Frequencia(2)
        );

        $recorrencia->incrementar();

        $this->assertSame(3, $recorrencia->frequencia()->valor());
    }
}
