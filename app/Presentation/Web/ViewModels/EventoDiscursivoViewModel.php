<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

final class EventoDiscursivoViewModel
{
    public function __construct(
        public readonly string $id,
        public readonly string $conteudo,
        public readonly int $posicao
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            id: (string) ($dados['id'] ?? ''),
            conteudo: (string) ($dados['conteudo'] ?? ''),
            posicao: (int) ($dados['posicao'] ?? 0)
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
