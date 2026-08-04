<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts\DTOs;

final class SalaCriadaDTO
{
    public function __construct(
        public readonly string $nomeSala,
        public readonly string $url
    ) {
    }
}
