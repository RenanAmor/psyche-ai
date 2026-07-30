<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Services\DetectorRecorrencias;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;

final class DetectorRecorrenciasTest extends TestCase
{
    public function testContaOcorrenciasPorConteudo(): void
    {
        $eventos = [
            new EventoDiscursivo(new Identificador('e1'), new ConteudoDiscursivo('lapso'), new Posicao(0)),
            new EventoDiscursivo(new Identificador('e2'), new ConteudoDiscursivo('lapso'), new Posicao(1)),
            new EventoDiscursivo(new Identificador('e3'), new ConteudoDiscursivo('chiste'), new Posicao(2)),
        ];

        $recorrencias = (new DetectorRecorrencias())->detectar($eventos);

        $this->assertSame(['lapso' => 2, 'chiste' => 1], $recorrencias);
    }

    public function testNormalizaEspacosEMaiusculasAoComparar(): void
    {
        $eventos = [
            new EventoDiscursivo(new Identificador('e1'), new ConteudoDiscursivo('lapso'), new Posicao(0)),
            new EventoDiscursivo(new Identificador('e2'), new ConteudoDiscursivo('Lapso '), new Posicao(1)),
            new EventoDiscursivo(new Identificador('e3'), new ConteudoDiscursivo(' LAPSO'), new Posicao(2)),
            new EventoDiscursivo(new Identificador('e4'), new ConteudoDiscursivo('Chiste'), new Posicao(3)),
        ];

        $recorrencias = (new DetectorRecorrencias())->detectar($eventos);

        $this->assertSame(['lapso' => 3, 'chiste' => 1], $recorrencias);
    }

    public function testNormalizarEPublicoEUsaAMesmaRegraDeDetectar(): void
    {
        $eventos = [
            new EventoDiscursivo(new Identificador('e1'), new ConteudoDiscursivo('Lapso'), new Posicao(0)),
        ];

        $recorrencias = (new DetectorRecorrencias())->detectar($eventos);

        $this->assertArrayHasKey(DetectorRecorrencias::normalizar(' LAPSO '), $recorrencias);
    }
}
