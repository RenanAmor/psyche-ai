<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

/**
 * Projeção somente-leitura de uma Observação (Discourse Engine, Sprint 14):
 * o texto registral já produzido por GeradorObservacoes — a interface
 * apenas exibe, nunca reformula ou acrescenta leitura.
 */
final class ObservacaoViewModel
{
    public function __construct(
        public readonly string $id,
        public readonly string $texto
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            id: (string) ($dados['id'] ?? ''),
            texto: (string) ($dados['texto'] ?? '')
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
