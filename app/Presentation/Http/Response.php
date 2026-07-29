<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Http;

/**
 * Resposta HTTP genérica: status, corpo já serializado e cabeçalhos.
 * JsonResponse é a única forma de construção usada pela API (Sprint 10
 * exige que toda resposta seja JSON), mas a classe permanece aberta a
 * outras representações futuras sem afetar Router/Controllers.
 */
class Response
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        protected readonly int $status,
        protected readonly string $corpo,
        protected readonly array $headers = []
    ) {
    }

    public function status(): int
    {
        return $this->status;
    }

    public function corpo(): string
    {
        return $this->corpo;
    }

    /**
     * @return array<string, string>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    public function enviar(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $nome => $valor) {
            header($nome . ': ' . $valor);
        }

        echo $this->corpo;
    }
}
