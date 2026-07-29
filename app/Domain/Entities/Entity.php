<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

abstract class Entity
{
    public function __construct(
        protected readonly string $id
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function equals(Entity $entity): bool
    {
        return static::class === $entity::class
            && $this->id === $entity->id();
    }
}