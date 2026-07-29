<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

final class Observacao extends Entity
{
    public function __construct(
        string $id,
        private readonly string $texto
    ) {
        parent::__construct($id);
    }

    public function texto(): string
    {
        return $this->texto;
    }
}