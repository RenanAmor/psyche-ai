<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use PHPUnit\Framework\TestCase;
use PsycheAI\Application\UseCases\GerarPerguntaSocratica\GerarPerguntaSocraticaCommand;
use PsycheAI\Application\UseCases\GerarPerguntaSocratica\GerarPerguntaSocraticaHandler;
use PsycheAI\Infrastructure\Contracts\DTOs\ContextoConversaDTO;
use PsycheAI\Infrastructure\Contracts\GeradorDePerguntaSocraticaInterface;

final class GerarPerguntaSocraticaHandlerTest extends TestCase
{
    public function testDelegaAoGeradorEDevolveAPerguntaNoResult(): void
    {
        $contexto = new ContextoConversaDTO(turnosRecentes: [], ehRepeticao: false, descricaoRecorrencia: null);

        $gerador = new class implements GeradorDePerguntaSocraticaInterface {
            public ?string $mensagemRecebida = null;
            public ?ContextoConversaDTO $contextoRecebido = null;

            public function gerar(string $mensagemUsuario, ContextoConversaDTO $contexto): ?string
            {
                $this->mensagemRecebida = $mensagemUsuario;
                $this->contextoRecebido = $contexto;

                return 'O que mais vem à mente?';
            }
        };

        $handler = new GerarPerguntaSocraticaHandler($gerador);
        $result = $handler->handle(new GerarPerguntaSocraticaCommand('Estou ansioso hoje.', $contexto));

        $this->assertSame('O que mais vem à mente?', $result->pergunta());
        $this->assertSame('Estou ansioso hoje.', $gerador->mensagemRecebida);
        $this->assertSame($contexto, $gerador->contextoRecebido);
    }

    public function testDevolveNullNoResultQuandoOGeradorDevolveNull(): void
    {
        $contexto = new ContextoConversaDTO(turnosRecentes: [], ehRepeticao: false, descricaoRecorrencia: null);

        $gerador = new class implements GeradorDePerguntaSocraticaInterface {
            public function gerar(string $mensagemUsuario, ContextoConversaDTO $contexto): ?string
            {
                return null;
            }
        };

        $handler = new GerarPerguntaSocraticaHandler($gerador);
        $result = $handler->handle(new GerarPerguntaSocraticaCommand('qualquer coisa', $contexto));

        $this->assertNull($result->pergunta());
    }
}
