<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use PsycheAI\Domain\ValueObjects\Identificador;

abstract class Entity
{
    public function __construct(
        protected readonly Identificador $id
    ) {
    }

    public function id(): Identificador
    {
        return $this->id;
    }

    public function equals(Entity $entity): bool
    {
        return static::class === $entity::class
            && $this->id->equals($entity->id());
    }
}