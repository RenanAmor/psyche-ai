<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Specifications\EventoValidoSpecification;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;

final class EventoValidoSpecificationTest extends TestCase
{
    public function testEventoComConteudoESatisfeito(): void
    {
        $evento = new EventoDiscursivo(new Identificador('e1'), new ConteudoDiscursivo('lapso'), new Posicao(0));

        $this->assertTrue((new EventoValidoSpecification())->isSatisfiedBy($evento));
    }
}
