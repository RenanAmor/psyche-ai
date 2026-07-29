<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

/**
 * Porta de despacho de mensagens (ex.: Commands da camada de Aplicação,
 * como `PsycheAI\Application\Contracts\CommandInterface`) a seus
 * respectivos handlers. Tipada com `object` genérico para que este
 * contrato de Infraestrutura não precise depender de tipos específicos
 * da Aplicação.
 */
interface MessageBusInterface
{
    public function dispatch(object $message): mixed;
}
