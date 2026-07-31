<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Http;

use RuntimeException;

/**
 * Exceção de transporte: carrega o status HTTP a ser retornado pelo
 * ExceptionHandler. Usada pelos Controllers e Requests HTTP para sinalizar
 * problemas da própria camada de transporte (corpo malformado, campo
 * obrigatório ausente, conflito de recurso) — nunca para regras de negócio,
 * que continuam pertencendo exclusivamente a Domain/Application.
 */
final class HttpException extends RuntimeException
{
    /**
     * @param string[] $erros
     */
    public function __construct(
        string $mensagem,
        private readonly int $statusCode,
        private readonly array $erros = []
    ) {
        parent::__construct($mensagem);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string[]
     */
    public function erros(): array
    {
        return $this->erros;
    }

    /**
     * @param string[] $erros
     */
    public static function badRequest(string $mensagem, array $erros = []): self
    {
        return new self($mensagem, 400, $erros);
    }

    public static function naoEncontrado(string $mensagem): self
    {
        return new self($mensagem, 404);
    }

    public static function conflito(string $mensagem): self
    {
        return new self($mensagem, 409);
    }

    public static function naoAutorizado(string $mensagem): self
    {
        return new self($mensagem, 401);
    }

    /**
     * @param string[] $erros
     */
    public static function naoProcessavel(string $mensagem, array $erros = []): self
    {
        return new self($mensagem, 422, $erros);
    }
}
