<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

/**
 * Um item da Linha do Tempo Discursiva de um Sujeito: uma Sessao, um
 * Discurso, um EventoDiscursivo ou uma MemoriaLongitudinal, projetados
 * para um formato comum que permite ordená-los e listá-los juntos em
 * ordem cronológica, sem interpretar seu conteúdo.
 */
final class LinhaDoTempoItemDTO
{
    public const TIPO_SESSAO = 'sessao';
    public const TIPO_DISCURSO = 'discurso';
    public const TIPO_EVENTO = 'evento';
    public const TIPO_MEMORIA = 'memoria';

    /**
     * @param array<string, mixed> $dados
     */
    public function __construct(
        public readonly string $tipo,
        public readonly string $id,
        public readonly string $timestamp,
        public readonly array $dados
    ) {
    }
}
