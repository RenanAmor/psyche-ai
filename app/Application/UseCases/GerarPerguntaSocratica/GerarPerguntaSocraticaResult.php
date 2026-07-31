<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\GerarPerguntaSocratica;

use PsycheAI\Application\Contracts\ResultInterface;

final class GerarPerguntaSocraticaResult implements ResultInterface
{
    public function __construct(
        private readonly ?string $pergunta
    ) {
    }

    public function pergunta(): ?string
    {
        return $this->pergunta;
    }
}
