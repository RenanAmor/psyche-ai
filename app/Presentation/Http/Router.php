<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Http;

/**
 * Router HTTP mínimo: casa método + path (com placeholders "{id}") contra
 * um handler registrado. Não conhece Application/Domain — apenas
 * despacha Request para o callable do Controller correspondente.
 */
final class Router
{
    /**
     * @var array<int, array{method: string, pattern: string, handler: callable}>
     */
    private array $rotas = [];

    public function get(string $path, callable $handler): void
    {
        $this->adicionar('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->adicionar('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->adicionar('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->adicionar('DELETE', $path, $handler);
    }

    private function adicionar(string $method, string $path, callable $handler): void
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $path);

        $this->rotas[] = [
            'method' => $method,
            'pattern' => '#^' . $pattern . '$#',
            'handler' => $handler,
        ];
    }

    public function despachar(Request $request): Response
    {
        foreach ($this->rotas as $rota) {
            if ($rota['method'] !== $request->metodo()) {
                continue;
            }

            if (preg_match($rota['pattern'], $request->path(), $matches) === 1) {
                /** @var array<string, string> $params */
                $params = array_filter(
                    $matches,
                    static fn (int|string $chave): bool => is_string($chave),
                    ARRAY_FILTER_USE_KEY
                );

                return ($rota['handler'])($request, $params);
            }
        }

        throw HttpException::naoEncontrado(
            sprintf('Rota "%s %s" não encontrada.', $request->metodo(), $request->path())
        );
    }
}
