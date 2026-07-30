<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\AI;

use PsycheAI\Infrastructure\Contracts\RespostaAutomaticaInterface;

/**
 * Implementação temporária de RespostaAutomaticaInterface: devolve
 * sempre a mesma resposta fixa, independente do conteúdo recebido.
 * Serve apenas para validar o fluxo completo da Sprint 12 (Interface →
 * API → Application → Domain → Persistência → Interface) — não contém
 * qualquer interpretação clínica. Substituída nas sprints futuras pelos
 * motores Freud e Lacan, que implementarão o mesmo contrato.
 */
final class RespostaFixaService implements RespostaAutomaticaInterface
{
    private const RESPOSTA_PADRAO = 'Recebi sua mensagem. Continue falando livremente.';

    public function responder(string $mensagemUsuario): string
    {
        return self::RESPOSTA_PADRAO;
    }
}
