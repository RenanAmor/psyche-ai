<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Recorrencia;
use PsycheAI\Domain\Services\GeradorObservacoes;
use PsycheAI\Domain\ValueObjects\Frequencia;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Texto;

final class GeradorObservacoesTest extends TestCase
{
    public function testGeraUmaObservacaoPorRecorrencia(): void
    {
        $recorrencia = new Recorrencia(
            new Identificador('r1'),
            new Texto('lapso de fala'),
            new Frequencia(3)
        );

        $observacoes = (new GeradorObservacoes())->gerar([$recorrencia]);

        $this->assertCount(1, $observacoes);
        $this->assertSame(
            'Recorrência observada: lapso de fala (3 ocorrência(s)).',
            $observacoes[0]->texto()->valor()
        );
    }
}
