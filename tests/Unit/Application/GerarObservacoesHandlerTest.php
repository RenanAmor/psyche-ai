<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use PHPUnit\Framework\TestCase;
use PsycheAI\Application\UseCases\GerarObservacoes\GerarObservacoesCommand;
use PsycheAI\Application\UseCases\GerarObservacoes\GerarObservacoesHandler;
use PsycheAI\Domain\Entities\Recorrencia;
use PsycheAI\Domain\ValueObjects\Frequencia;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Texto;

final class GerarObservacoesHandlerTest extends TestCase
{
    public function testGeraUmaObservacaoPorRecorrencia(): void
    {
        $recorrencias = [
            new Recorrencia(new Identificador('r1'), new Texto('lapso'), new Frequencia(2)),
        ];

        $handler = new GerarObservacoesHandler();
        $result = $handler->handle(new GerarObservacoesCommand($recorrencias));

        $observacoes = $result->observacoes();
        $this->assertCount(1, $observacoes);
        $this->assertSame(
            'Recorrência observada: lapso (2 ocorrência(s)).',
            $observacoes[0]->texto()->valor()
        );

        $dto = $result->dtos()[0];
        $this->assertSame('Recorrência observada: lapso (2 ocorrência(s)).', $dto->texto);
    }
}
