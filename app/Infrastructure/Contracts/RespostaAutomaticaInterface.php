<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

/**
 * Porta de geração da resposta automática do sistema a uma mensagem do
 * usuário, independente de qual mecanismo concreto está por trás dela.
 * Na Sprint 12 a única implementação é uma resposta fixa temporária
 * (RespostaFixaService); nas sprints futuras dos motores Freud e Lacan,
 * novas implementações substituirão essa sem alterar quem consome esta
 * porta (MensagemApplicationService).
 */
interface RespostaAutomaticaInterface
{
    public function responder(string $mensagemUsuario): string;
}
