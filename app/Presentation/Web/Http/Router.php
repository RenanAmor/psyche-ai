<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Http;

/**
 * Router mínimo da interface web: casa método + path (com placeholders
 * "{id}") contra um handler registrado e despacha a Request para a
 * Response correspondente. Quando nenhuma rota casa, delega para o
 * handler de "não encontrado" configurado, em vez de lançar exceção —
 * a interface web sempre deve devolver uma página, nunca um erro fatal.
 */
final class Router
{
    /**
     * @var array<int, array{method: string, pattern: string, handler: callable}>
     */
    private array $rotas = [];

    /** @var callable|null */
    private $handlerNaoEncontrado = null;

    public function get(string $path, callable $handler): void
    {
        $this->adicionar('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->adicionar('POST', $path, $handler);
    }

    public function naoEncontradoHandler(callable $handler): void
    {
        $this->handlerNaoEncontrado = $handler;
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
            if ($rota['method'] !== $request->method) {
                continue;
            }

            if (preg_match($rota['pattern'], $request->path, $matches) === 1) {
                /** @var array<string, string> $params */
                $params = array_filter(
                    $matches,
                    static fn (int|string $chave): bool => is_string($chave),
                    ARRAY_FILTER_USE_KEY
                );

                return ($rota['handler'])($request->comRouteParams($params));
            }
        }

        if ($this->handlerNaoEncontrado !== null) {
            return ($this->handlerNaoEncontrado)($request);
        }

        return Response::naoEncontrado(sprintf('Rota "%s %s" não encontrada.', $request->method, $request->path));
    }
}
