<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;

final class EventoDiscursivoTest extends TestCase
{
    public function testExposesConteudoEPosicao(): void
    {
        $evento = new EventoDiscursivo(
            new Identificador('e1'),
            new ConteudoDiscursivo('lapso'),
            new Posicao(3)
        );

        $this->assertSame('lapso', $evento->conteudo()->valor());
        $this->assertSame(3, $evento->posicao()->valor());
    }
}
