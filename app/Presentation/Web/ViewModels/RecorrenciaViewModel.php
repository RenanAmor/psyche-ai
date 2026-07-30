<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

/**
 * Projeção somente-leitura de uma Recorrência (Discourse Engine, Sprint 14):
 * a descrição literal do que se repete e sua frequência (Motor Freud), mais
 * o rótulo lacaniano opcional (Motor Lacan, Sprint 16) — sempre uma
 * reclassificação da mesma recorrência, nunca uma hipótese ou leitura de
 * sentido nova.
 */
final class RecorrenciaViewModel
{
    public function __construct(
        public readonly string $id,
        public readonly string $descricao,
        public readonly int $frequencia,
        public readonly ?string $rotuloLacaniano = null
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
            frequencia: (int) ($dados['frequencia'] ?? 0),
            rotuloLacaniano: isset($dados['rotuloLacaniano']) ? (string) $dados['rotuloLacaniano'] : null
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
