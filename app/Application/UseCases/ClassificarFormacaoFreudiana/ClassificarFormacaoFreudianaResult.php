<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\ClassificarFormacaoFreudiana;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Domain\ValueObjects\TipoFormacaoFreudiana;

final class ClassificarFormacaoFreudianaResult implements ResultInterface
{
    public function __construct(
        private readonly TipoFormacaoFreudiana $tipo
    ) {
    }

    public function tipo(): TipoFormacaoFreudiana
    {
        return $this->tipo;
    }
}
