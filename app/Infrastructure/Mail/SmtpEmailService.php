<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Mail;

use DateTimeImmutable;
use PsycheAI\Infrastructure\Contracts\EmailInterface;
use RuntimeException;

/**
 * Adapter fino sobre o suporte nativo do cURL a SMTP (mesmo padrão de
 * AnthropicLLMService/OpenAIWhisperTranscriptionService: credenciais lidas
 * de variável de ambiente, parâmetros do construtor só para injeção em
 * teste). Autentica na caixa configurada (produção: contato@investimentos369.com,
 * via smtp.hostinger.com:465) e envia uma mensagem MIME simples em texto
 * puro — sem fila, sem retry: falha de envio deve ser tratada por quem
 * chama (ver ListaDeEsperaApplicationService), nunca aqui.
 */
final class SmtpEmailService implements EmailInterface
{
    public function __construct(
        private readonly ?string $host = null,
        private readonly ?int $porta = null,
        private readonly ?string $usuario = null,
        private readonly ?string $senha = null,
        private readonly ?string $remetenteEmail = null,
        private readonly ?string $remetenteNome = null
    ) {
    }

    public function enviar(string $destinatarioEmail, string $destinatarioNome, string $assunto, string $corpoTexto): void
    {
        $host = $this->host ?? (getenv('SMTP_HOST') ?: '');
        $porta = $this->porta ?? ((int) (getenv('SMTP_PORT') ?: 465));
        $usuario = $this->usuario ?? (getenv('SMTP_USER') ?: '');
        $senha = $this->senha ?? (getenv('SMTP_PASSWORD') ?: '');
        $remetenteEmail = $this->remetenteEmail ?? (getenv('MAIL_FROM_ADDRESS') ?: $usuario);
        $remetenteNome = $this->remetenteNome ?? (getenv('MAIL_FROM_NAME') ?: 'PsycheAI');

        $mensagem = self::montarMensagemMime(
            $remetenteEmail,
            $remetenteNome,
            $destinatarioEmail,
            $destinatarioNome,
            $assunto,
            $corpoTexto
        );

        $streamMensagem = fopen('php://temp', 'r+');
        fwrite($streamMensagem, $mensagem);
        rewind($streamMensagem);

        $handle = curl_init();
        curl_setopt_array($handle, [
            CURLOPT_URL => sprintf('smtps://%s:%d', $host, $porta),
            CURLOPT_USERNAME => $usuario,
            CURLOPT_PASSWORD => $senha,
            CURLOPT_MAIL_FROM => sprintf('<%s>', $remetenteEmail),
            CURLOPT_MAIL_RCPT => [sprintf('<%s>', $destinatarioEmail)],
            CURLOPT_UPLOAD => true,
            CURLOPT_READDATA => $streamMensagem,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 20,
        ]);

        $sucesso = curl_exec($handle);
        $erroCurl = curl_errno($handle);
        $mensagemErroCurl = curl_error($handle);
        fclose($streamMensagem);

        if ($erroCurl !== 0 || $sucesso === false) {
            throw new RuntimeException(sprintf('Falha ao enviar e-mail via SMTP: %s', $mensagemErroCurl));
        }
    }

    private static function montarMensagemMime(
        string $remetenteEmail,
        string $remetenteNome,
        string $destinatarioEmail,
        string $destinatarioNome,
        string $assunto,
        string $corpoTexto
    ): string {
        $cabecalhos = [
            'Date' => (new DateTimeImmutable())->format(DATE_RFC2822),
            'To' => sprintf('%s <%s>', self::codificarCabecalho($destinatarioNome), $destinatarioEmail),
            'From' => sprintf('%s <%s>', self::codificarCabecalho($remetenteNome), $remetenteEmail),
            'Subject' => self::codificarCabecalho($assunto),
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Transfer-Encoding' => '8bit',
        ];

        $linhas = [];

        foreach ($cabecalhos as $nome => $valor) {
            $linhas[] = sprintf('%s: %s', $nome, $valor);
        }

        $linhas[] = '';
        $linhas[] = str_replace("\n", "\r\n", $corpoTexto);

        return implode("\r\n", $linhas);
    }

    private static function codificarCabecalho(string $texto): string
    {
        return mb_encode_mimeheader($texto, 'UTF-8', 'B', "\r\n");
    }
}
