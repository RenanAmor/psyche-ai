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

    /**
     * Envia um corpo binário bruto (Sprint 22, captura de áudio) — a API
     * ainda responde no envelope JSON padrão, só o corpo da requisição
     * foge do formato form/JSON dos demais métodos.
     */
    public function postBinario(string $recurso, string $binario): ApiResponse;

    /**
     * Busca um recurso cuja resposta é o próprio binário (ex.: o áudio
     * original de uma sessão), não um envelope JSON.
     */
    public function getBinario(string $recurso): BinaryApiResponse;
}
