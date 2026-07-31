<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Http;

/**
 * Requisição HTTP da interface web (HTML), independente da Request da API
 * REST (Presentation\Http\Request). Carrega apenas o necessário para
 * roteamento e leitura de parâmetros de página — nenhuma regra de negócio.
 */
final class Request
{
    /**
     * @param array<string, string> $query
     * @param array<string, string> $body
     * @param array<string, string> $routeParams
     */
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly array $query = [],
        public readonly array $body = [],
        public readonly array $routeParams = [],
        private readonly string $corpoBinario = ''
    ) {
    }

    public static function criar(
        string $method,
        string $path,
        array $query = [],
        array $body = [],
        string $corpoBinario = ''
    ): self {
        $normalizado = '/' . ltrim((string) parse_url($path, PHP_URL_PATH), '/');
        $normalizado = $normalizado === '/' ? $normalizado : rtrim($normalizado, '/');

        return new self(strtoupper($method), $normalizado, $query, $body, corpoBinario: $corpoBinario);
    }

    /**
     * @param array<string, string> $routeParams
     */
    public function comRouteParams(array $routeParams): self
    {
        return new self($this->method, $this->path, $this->query, $this->body, $routeParams, $this->corpoBinario);
    }

    /**
     * Corpo bruto da requisição (Sprint 22, upload de áudio da tela
     * /conversa) — nunca populado a partir de $_POST, que só carrega
     * campos de formulário form-encoded.
     */
    public function corpoBinario(): string
    {
        return $this->corpoBinario;
    }

    public function query(string $chave, ?string $padrao = null): ?string
    {
        return $this->query[$chave] ?? $padrao;
    }

    public function input(string $chave, ?string $padrao = null): ?string
    {
        return $this->body[$chave] ?? $padrao;
    }

    public function routeParam(string $chave, ?string $padrao = null): ?string
    {
        return $this->routeParams[$chave] ?? $padrao;
    }
}
