<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;

final class DiscursoTest extends TestCase
{
    public function testExposesConteudo(): void
    {
        $discurso = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('conteúdo produzido'));

        $this->assertSame('conteúdo produzido', $discurso->conteudo()->valor());
    }

    public function testAcumulaEventos(): void
    {
        $discurso = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('conteúdo'));
        $evento = new EventoDiscursivo(
            new Identificador('e1'),
            new ConteudoDiscursivo('evento'),
            new Posicao(0)
        );

        $discurso->adicionarEvento($evento);

        $this->assertSame([$evento], $discurso->eventos());
    }
}
