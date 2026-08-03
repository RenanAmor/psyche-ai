<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Support;

use PsycheAI\Infrastructure\Contracts\EmailInterface;
use RuntimeException;

/**
 * Duplo de teste de EmailInterface: registra cada chamada (ou lança uma
 * falha simulada), sem nenhuma chamada de rede real — SmtpEmailService é
 * a implementação de produção, verificada separadamente.
 */
final class EmailSpy implements EmailInterface
{
    /**
     * @var array<int, array{destinatarioEmail: string, destinatarioNome: string, assunto: string, corpoTexto: string}>
     */
    public array $enviados = [];

    public function __construct(
        private readonly ?RuntimeException $falha = null
    ) {
    }

    public function enviar(string $destinatarioEmail, string $destinatarioNome, string $assunto, string $corpoTexto): void
    {
        if ($this->falha !== null) {
            throw $this->falha;
        }

        $this->enviados[] = [
            'destinatarioEmail' => $destinatarioEmail,
            'destinatarioNome' => $destinatarioNome,
            'assunto' => $assunto,
            'corpoTexto' => $corpoTexto,
        ];
    }
}
