<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Client;

/**
 * Porta de comunicação da interface web com a API REST. A única
 * implementação de produção é ApiHttpClient, que fala HTTP de verdade com
 * a API da Sprint 10 — Controllers dependem exclusivamente desta
 * interface e nunca conhecem a implementação concreta.
 */
interface HttpClientInterface
{
    /**
     * @param array<string, string> $parametros
     */
    public function get(string $recurso, array $parametros = []): ApiResponse;

    /**
     * @param array<string, string> $dados
     */
    public function post(string $recurso, array $dados = []): ApiResponse;

    /**
     * @param array<string, string> $dados
     */
    public function put(string $recurso, array $dados = []): ApiResponse;

    public function delete(string $recurso): ApiResponse;
}
