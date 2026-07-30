<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

/**
 * Consolidação puramente quantitativa da produção discursiva de um
 * Sujeito até o momento da consulta: quantas Sessões, Discursos, Eventos
 * Discursivos e Memórias Longitudinais existem. Nenhuma interpretação —
 * apenas contagem.
 */
final class ConsolidacaoMemoriaDTO
{
    public function __construct(
        public readonly string $sujeitoId,
        public readonly int $quantidadeDeSessoes,
        public readonly int $quantidadeDeDiscursos,
        public readonly int $quantidadeDeEventosDiscursivos,
        public readonly int $quantidadeDeMemorias
    ) {
    }
}
