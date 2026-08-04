<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\ValueObjects\ConteudoAnotacao;

final class ConteudoAnotacaoTest extends TestCase
{
    public function testAceitaStringVazia(): void
    {
        $vazio = new ConteudoAnotacao('');

        $this->assertSame('', $vazio->valor());
    }

    public function testAceitaTextoNormal(): void
    {
        $anotacao = new ConteudoAnotacao('Paciente relatou ansiedade.');

        $this->assertSame('Paciente relatou ansiedade.', $anotacao->valor());
    }

    public function testRejeitaTextoAcimaDoTamanhoMaximo(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ConteudoAnotacao(str_repeat('a', 50001));
    }
}
