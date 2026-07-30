<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

final class ConsolidacaoViewModel
{
    public function __construct(
        public readonly string $sujeitoId,
        public readonly int $quantidadeDeSessoes,
        public readonly int $quantidadeDeDiscursos,
        public readonly int $quantidadeDeEventosDiscursivos,
        public readonly int $quantidadeDeMemorias
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            sujeitoId: (string) ($dados['sujeitoId'] ?? ''),
            quantidadeDeSessoes: (int) ($dados['quantidadeDeSessoes'] ?? 0),
            quantidadeDeDiscursos: (int) ($dados['quantidadeDeDiscursos'] ?? 0),
            quantidadeDeEventosDiscursivos: (int) ($dados['quantidadeDeEventosDiscursivos'] ?? 0),
            quantidadeDeMemorias: (int) ($dados['quantidadeDeMemorias'] ?? 0)
        );
    }
}
