<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\GerarPerguntaSocratica;

use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Infrastructure\Contracts\GeradorDePerguntaSocraticaInterface;

/**
 * Sem valor default para $gerador: ao contrário de handlers que envolvem
 * apenas Domain Services puros, a implementação concreta aqui fala com
 * uma API externa e precisa de credencial — instanciá-la implicitamente
 * esconderia essa dependência. Quem monta este Handler (o composition
 * root, ApplicationServiceProvider) decide explicitamente qual
 * GeradorDePerguntaSocraticaInterface injetar.
 */
final class GerarPerguntaSocraticaHandler implements UseCaseInterface
{
    public function __construct(
        private readonly GeradorDePerguntaSocraticaInterface $gerador
    ) {
    }

    public function handle(GerarPerguntaSocraticaCommand $command): GerarPerguntaSocraticaResult
    {
        $pergunta = $this->gerador->gerar($command->mensagemUsuario, $command->contexto);

        return new GerarPerguntaSocraticaResult($pergunta);
    }
}
