<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

/**
 * Projeção somente-leitura de uma ocorrência do circuito/trajeto de uma
 * Recorrencia (revisão pós-Sprint 16): apenas onde/quando ela reapareceu
 * — Sessão, Discurso, Evento Discursivo e o momento (data da Sessão).
 */
final class OcorrenciaCircuitoViewModel
{
    public function __construct(
        public readonly string $sessaoId,
        public readonly string $discursoId,
        public readonly string $eventoId,
        public readonly string $momento,
        public readonly int $posicao
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            sessaoId: (string) ($dados['sessaoId'] ?? ''),
            discursoId: (string) ($dados['discursoId'] ?? ''),
            eventoId: (string) ($dados['eventoId'] ?? ''),
            momento: (string) ($dados['momento'] ?? ''),
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
