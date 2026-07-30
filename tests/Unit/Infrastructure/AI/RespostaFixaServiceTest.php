<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure\AI;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\AI\RespostaFixaService;
use PsycheAI\Infrastructure\Contracts\RespostaAutomaticaInterface;

final class RespostaFixaServiceTest extends TestCase
{
    public function testImplementaRespostaAutomaticaInterface(): void
    {
        $this->assertInstanceOf(RespostaAutomaticaInterface::class, new RespostaFixaService());
    }

    public function testSempreDevolveAMesmaRespostaFixaIndependenteDaMensagem(): void
    {
        $servico = new RespostaFixaService();

        $this->assertSame(
            'Recebi sua mensagem. Continue falando livremente.',
            $servico->responder('Estou me sentindo ansioso hoje.')
        );
        $this->assertSame(
            'Recebi sua mensagem. Continue falando livremente.',
            $servico->responder('')
        );
    }
}
