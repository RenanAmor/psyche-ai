<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Observacao;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\ObservacaoTexto;

final class ObservacaoTest extends TestCase
{
    public function testExposesTexto(): void
    {
        $observacao = new Observacao(new Identificador('o1'), new ObservacaoTexto('fato observado'));

        $this->assertSame('fato observado', $observacao->texto()->valor());
    }
}
