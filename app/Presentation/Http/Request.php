<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Http;

/**
 * Representa uma requisição HTTP já normalizada (método, path, corpo JSON
 * decodificado e query string), independente de como foi capturada — de
 * $_SERVER/php://input em produção, ou construída diretamente nos testes.
 */
final class Request
{
    /**
     * @param array<string, mixed> $corpo
     * @param array<string, mixed> $query
     */
    private function __construct(
        private readonly string $metodo,
        private readonly string $path,
        private readonly array $corpo,
        private readonly array $query
    ) {
    }

    public static function capturarDoGlobals(): self
    {
        $metodo = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $path = self::normalizarPath((string) ($_SERVER['REQUEST_URI'] ?? '/'));
        $corpo = self::decodificarCorpo((string) (file_get_contents('php://input') ?: ''));

        return new self($metodo, $path, $corpo, $_GET);
    }

    /**
     * @param array<string, mixed> $corpo
     * @param array<string, mixed> $query
     */
    public static function criar(string $metodo, string $path, array $corpo = [], array $query = []): self
    {
        return new self(strtoupper($metodo), self::normalizarPath($path), $corpo, $query);
    }

    private static function normalizarPath(string $uri): string
    {
        $path = (string) parse_url($uri, PHP_URL_PATH);
        $path = '/' . ltrim($path, '/');

        return $path === '/' ? $path : rtrim($path, '/');
    }

    /**
     * @return array<string, mixed>
     */
    private static function decodificarCorpo(string $conteudo): array
    {
        if (trim($conteudo) === '') {
            return [];
        }

        $decodificado = json_decode($conteudo, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decodificado)) {
            throw HttpException::badRequest('O corpo da requisição deve ser um JSON válido.');
        }

        /** @var array<string, mixed> $decodificado */
        return $decodificado;
    }

    public function metodo(): string
    {
        return $this->metodo;
    }

    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return array<string, mixed>
     */
    public function corpo(): array
    {
        return $this->corpo;
    }

    public function query(string $chave, mixed $default = null): mixed
    {
        return $this->query[$chave] ?? $default;
    }
}
