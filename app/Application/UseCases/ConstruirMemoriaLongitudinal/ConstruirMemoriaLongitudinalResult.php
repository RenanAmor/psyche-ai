<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Application\DTOs\MemoriaLongitudinalDTO;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;

final class ConstruirMemoriaLongitudinalResult implements ResultInterface
{
    public function __construct(
        private readonly MemoriaLongitudinal $memoria
    ) {
    }

    public function memoria(): MemoriaLongitudinal
    {
        return $this->memoria;
    }

    public function dto(): MemoriaLongitudinalDTO
    {
        return MemoriaLongitudinalDTO::fromEntity($this->memoria);
    }
}
