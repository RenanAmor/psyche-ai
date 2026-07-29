<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Feature\Http;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Persistence\SQLite\Connection;
use PsycheAI\Infrastructure\Providers\ApplicationServiceProvider;
use PsycheAI\Presentation\Http\ExceptionHandler;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Http\Router;
use PsycheAI\Presentation\Routes;
use Throwable;

/**
 * Exercita a API de ponta a ponta (Router -> Controller -> Application ->
 * Persistence) sem subir um servidor HTTP real: cada teste monta um
 * ApplicationServiceProvider sobre SQLite em memória, registra as rotas
 * da Sprint 10 e despacha requisições construídas diretamente.
 */
abstract class HttpTestCase extends TestCase
{
    protected Router $router;
    protected ApplicationServiceProvider $provider;

    protected function setUp(): void
    {
        $pdo = (new Connection(':memory:'))->pdo();
        $this->provider = ApplicationServiceProvider::comPDO($pdo);

        $this->router = new Router();
        Routes::registrar($this->router, $this->provider);
    }

    /**
     * @param array<string, mixed> $corpo
     */
    protected function despachar(string $metodo, string $path, array $corpo = []): JsonResponse
    {
        $request = Request::criar($metodo, $path, $corpo);

        try {
            $response = $this->router->despachar($request);
        } catch (Throwable $erro) {
            $response = ExceptionHandler::converter($erro);
        }

        self::assertInstanceOf(JsonResponse::class, $response);

        return $response;
    }

    /**
     * @return array<string, mixed>
     */
    protected function decodificar(JsonResponse $response): array
    {
        if ($response->corpo() === '') {
            return [];
        }

        /** @var array<string, mixed> $decodificado */
        $decodificado = json_decode($response->corpo(), true);

        return $decodificado;
    }
}
