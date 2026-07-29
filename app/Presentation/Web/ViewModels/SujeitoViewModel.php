<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

/**
 * Projeção somente-leitura de um Sujeito para exibição na interface.
 * Construída a partir do array devolvido pelo Cliente HTTP (hoje
 * mockado, futuramente a API REST) — nunca a partir de uma Entidade de
 * Domínio, que a Presentation não pode conhecer.
 */
final class SujeitoViewModel
{
    public function __construct(
        public readonly string $id,
        public readonly string $nome,
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
            nome: (string) ($dados['nome'] ?? ''),
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
