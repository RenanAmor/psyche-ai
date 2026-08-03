<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

/**
 * Porta de envio de e-mail transacional, independente de provedor (SMTP,
 * API de terceiros etc.).
 */
interface EmailInterface
{
    public function enviar(string $destinatarioEmail, string $destinatarioNome, string $assunto, string $corpoTexto): void;
}
