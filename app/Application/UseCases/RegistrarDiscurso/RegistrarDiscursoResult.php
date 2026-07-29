<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarDiscurso;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Application\DTOs\DiscursoDTO;
use PsycheAI\Domain\Entities\Discurso;

final class RegistrarDiscursoResult implements ResultInterface
{
    public function __construct(
        private readonly Discurso $discurso
    ) {
    }

    public function discurso(): Discurso
    {
        return $this->discurso;
    }

    public function dto(): DiscursoDTO
    {
        return DiscursoDTO::fromEntity($this->discurso);
    }
}
