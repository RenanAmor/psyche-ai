<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Client;

/**
 * Porta de comunicação da interface web com a API REST. Nesta Sprint
 * apenas a estrutura existe — a única implementação disponível é
 * MockApiHttpClient. Uma implementação futura (ex.: HttpClient sobre
 * cURL/Guzzle) substituirá o mock sem exigir mudanças em Controllers,
 * que dependem exclusivamente desta interface.
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
}
