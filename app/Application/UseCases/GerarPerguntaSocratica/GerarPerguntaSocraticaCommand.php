<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\GerarPerguntaSocratica;

use PsycheAI\Application\Contracts\CommandInterface;
use PsycheAI\Infrastructure\Contracts\DTOs\ContextoConversaDTO;

final class GerarPerguntaSocraticaCommand implements CommandInterface
{
    public function __construct(
        public readonly string $mensagemUsuario,
        public readonly ContextoConversaDTO $contexto
    ) {
    }
}
