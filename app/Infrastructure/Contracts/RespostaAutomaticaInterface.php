<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

/**
 * Porta de geração da resposta automática do sistema a uma mensagem do
 * usuário, independente de qual mecanismo concreto está por trás dela.
 * Na Sprint 12 a única implementação era uma resposta fixa temporária
 * (RespostaFixaService); a partir da Sprint 17, RespostaEcoRecorrenciaService
 * passa a ser a implementação padrão, refletindo (sem interpretar) as
 * repetições que o Motor Freud (Sprint 15) já detecta no histórico do
 * Sujeito — por isso o contrato ganha $sujeitoId, o dado mínimo que
 * qualquer implementação baseada em recorrências precisa para localizar
 * esse histórico. O parâmetro tem valor padrão para não quebrar
 * implementações (como RespostaFixaService) que não precisam dele.
 */
interface RespostaAutomaticaInterface
{
    public function responder(string $mensagemUsuario, string $sujeitoId = ''): string;
}
