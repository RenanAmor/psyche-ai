<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\AI;

use PsycheAI\Infrastructure\Contracts\RespostaAutomaticaInterface;

/**
 * Implementação temporária de RespostaAutomaticaInterface: devolve
 * sempre a mesma resposta fixa, independente do conteúdo recebido.
 * Serviu para validar o fluxo completo da Sprint 12 (Interface → API →
 * Application → Domain → Persistência → Interface) — não contém
 * qualquer interpretação clínica. Deixou de ser o binding padrão na
 * Sprint 17 (ver ApplicationServiceProvider), mas continua em uso como
 * resposta de reserva de RespostaEcoRecorrenciaService para quando ainda
 * não há nenhuma repetição a refletir.
 */
final class RespostaFixaService implements RespostaAutomaticaInterface
{
    private const RESPOSTA_PADRAO = 'Recebi sua mensagem. Continue falando livremente.';

    public function responder(string $mensagemUsuario, string $sujeitoId = '', string $sessaoId = ''): string
    {
        return self::RESPOSTA_PADRAO;
    }
}
