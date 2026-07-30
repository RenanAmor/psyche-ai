<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

/**
 * Projeção somente-leitura de uma Recorrência (Discourse Engine, Sprint 14)
 * para exibição na interface: apenas a descrição literal do que se repete
 * e sua frequência — nenhuma hipótese, interpretação ou rótulo de sentido.
 */
final class RecorrenciaViewModel
{
    public function __construct(
        public readonly string $id,
        public readonly string $descricao,
        public readonly int $frequencia
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            id: (string) ($dados['id'] ?? ''),
            descricao: (string) ($dados['descricao'] ?? ''),
            frequencia: (int) ($dados['frequencia'] ?? 0)
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
