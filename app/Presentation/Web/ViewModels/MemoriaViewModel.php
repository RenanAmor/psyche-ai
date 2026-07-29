<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

final class MemoriaViewModel
{
    public function __construct(
        public readonly string $id,
        public readonly int $quantidadeDeSessoes
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            id: (string) ($dados['id'] ?? ''),
            quantidadeDeSessoes: (int) ($dados['quantidadeDeSessoes'] ?? 0)
        );
    }

    /**
     * @param array<int, array<string, mixed>> $lista
     * @return self[]
     */
    public static function fromArrayList(array $lista): array
    {
        return array_map(self::fromArray(...), $lista);
    }
}
