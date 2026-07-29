<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\GerarObservacoes;

use PsycheAI\Application\Contracts\CommandInterface;
use PsycheAI\Domain\Entities\Recorrencia;

final class GerarObservacoesCommand implements CommandInterface
{
    /**
     * @param Recorrencia[] $recorrencias
     */
    public function __construct(
        public readonly array $recorrencias
    ) {
    }
}
