<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Http;

/**
 * Representa uma requisição HTTP já normalizada (método, path, corpo e
 * query string), independente de como foi capturada — de
 * $_SERVER/php://input em produção, ou construída diretamente nos testes.
 *
 * O corpo bruto é sempre preservado como string; a decodificação JSON
 * (exigida pela API desde a Sprint 10) só acontece sob demanda, em
 * corpo() — lazy, para que rotas de corpo binário (Sprint 22, upload de
 * áudio) possam ler os mesmos bytes via corpoBinario() sem disparar
 * HttpException::badRequest() por não ser um JSON válido.
 */
final class Request
{
    /**
     * @var array<string, mixed>|null
     */
    private ?array $corpoDecodificado = null;

    /**
     * @param array<string, mixed> $query
     * @param array<string, string> $headers chave já normalizada em minúsculas
     */
    private function __construct(
        private readonly string $metodo,
        private readonly string $path,
        private readonly string $corpoBruto,
        private readonly array $query,
        private readonly array $headers = []
    ) {
    }

    public static function capturarDoGlobals(): self
    {
        $metodo = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $path = self::normalizarPath((string) ($_SERVER['REQUEST_URI'] ?? '/'));
        $corpoBruto = (string) (file_get_contents('php://input') ?: '');

        return new self($metodo, $path, $corpoBruto, $_GET, self::capturarCabecalhosDoGlobals());
    }

    /**
     * @return array<string, string>
     */
    private static function capturarCabecalhosDoGlobals(): array
    {
        $headers = [];

        foreach ($_SERVER as $chave => $valor) {
            if (!is_string($chave) || !is_string($valor) || !str_starts_with($chave, 'HTTP_')) {
                continue;
            }

            $nome = strtolower(str_replace('_', '-', substr($chave, 5)));
            $headers[$nome] = $valor;
        }

        return $headers;
    }

    /**
     * @param array<string, mixed> $corpo
     * @param array<string, mixed> $query
     * @param array<string, string> $headers
     */
    public static function criar(string $metodo, string $path, array $corpo = [], array $query = [], array $headers = []): self
    {
        $instancia = new self(strtoupper($metodo), self::normalizarPath($path), '', $query, self::normalizarChavesDeCabecalho($headers));
        $instancia->corpoDecodificado = $corpo;

        return $instancia;
    }

    /**
     * Constrói uma requisição de corpo binário bruto (Sprint 22, upload de
     * áudio) — nunca decodificado como JSON.
     *
     * @param array<string, mixed> $query
     */
    public static function criarComCorpoBinario(string $metodo, string $path, string $corpoBinario, array $query = []): self
    {
        return new self(strtoupper($metodo), self::normalizarPath($path), $corpoBinario, $query);
    }

    /**
     * @param array<string, string> $headers
     * @return array<string, string>
     */
    private static function normalizarChavesDeCabecalho(array $headers): array
    {
        $normalizado = [];

        foreach ($headers as $nome => $valor) {
            $normalizado[strtolower($nome)] = $valor;
        }

        return $normalizado;
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
        return $this->corpoDecodificado ??= self::decodificarCorpo($this->corpoBruto);
    }

    /**
     * Corpo bruto, sem nenhuma tentativa de decodificação — usado pelas
     * rotas de upload binário (Sprint 22), onde o corpo é o próprio áudio,
     * não JSON.
     */
    public function corpoBinario(): string
    {
        return $this->corpoBruto;
    }

    public function query(string $chave, mixed $default = null): mixed
    {
        return $this->query[$chave] ?? $default;
    }

    public function cabecalho(string $nome): ?string
    {
        return $this->headers[strtolower($nome)] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function queries(): array
    {
        return $this->query;
    }
}
