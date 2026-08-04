<?php

declare(strict_types=1);

namespace PsycheAI\Domain\ValueObjects;

/**
 * Quem produziu um EventoDiscursivo ou uma GravacaoAudio. Antes da
 * Videochamada Embutida (trilha por participante via Daily.co), o único
 * sinal de autoria era a paridade da Posicao — frágil e já quebrada para
 * transcrição de áudio com múltiplos segmentos. `Desconhecido` existe só
 * para dados legados/administrativos onde a origem real não é rastreável.
 */
enum Locutor: string
{
    case Sujeito = 'sujeito';
    case Analista = 'analista';
    case Sistema = 'sistema';
    case Desconhecido = 'desconhecido';
}
