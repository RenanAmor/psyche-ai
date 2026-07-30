<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use PHPUnit\Framework\TestCase;
use PsycheAI\Application\UseCases\ClassificarFormacaoFreudiana\ClassificarFormacaoFreudianaCommand;
use PsycheAI\Application\UseCases\ClassificarFormacaoFreudiana\ClassificarFormacaoFreudianaHandler;
use PsycheAI\Domain\ValueObjects\TipoFormacaoFreudiana;
use PsycheAI\Infrastructure\Contracts\ClassificadorEstruturalInterface;

final class ClassificarFormacaoFreudianaHandlerTest extends TestCase
{
    public function testDelegaAoClassificadorEDevolveOTipoNoResult(): void
    {
        $classificador = new class implements ClassificadorEstruturalInterface {
            public ?string $conteudoRecebido = null;

            public function classificar(string $conteudo): TipoFormacaoFreudiana
            {
                $this->conteudoRecebido = $conteudo;

                return TipoFormacaoFreudiana::Chiste;
            }
        };

        $handler = new ClassificarFormacaoFreudianaHandler($classificador);
        $result = $handler->handle(new ClassificarFormacaoFreudianaCommand('um trocadilho qualquer'));

        $this->assertSame(TipoFormacaoFreudiana::Chiste, $result->tipo());
        $this->assertSame('um trocadilho qualquer', $classificador->conteudoRecebido);
    }
}
