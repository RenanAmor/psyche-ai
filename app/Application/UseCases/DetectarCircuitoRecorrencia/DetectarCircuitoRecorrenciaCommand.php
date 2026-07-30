<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\DetectarCircuitoRecorrencia;

use PsycheAI\Application\Contracts\CommandInterface;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;
use PsycheAI\Domain\Entities\Recorrencia;

final class DetectarCircuitoRecorrenciaCommand implements CommandInterface
{
    /**
     * @param Recorrencia[] $recorrencias já filtradas pelo limiar mínimo
     *        (CicloDeObservacaoService::executar()) — única fonte de quais
     *        recorrências existem; o circuito nunca introduz uma
     *        recorrência que o Motor Freud não tenha trazido.
     */
    public function __construct(
        public readonly MemoriaLongitudinal $memoria,
        public readonly array $recorrencias
    ) {
    }
}
