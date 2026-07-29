<?php

declare(strict_types=1);

/**
 * Roteador HTTP mínimo usado apenas por ApiHttpClientTest para produzir,
 * de forma real (via `php -S`), respostas HTTP que a API saudável da
 * Sprint 10 nunca produziria por si mesma (500, corpo não-JSON) — sem
 * isso, o mapeamento de status de ApiHttpClient para esses casos ficaria
 * sem cobertura de teste. Não faz parte da aplicação.
 */

$path = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);

header('Content-Type: application/json; charset=utf-8');

match ($path) {
    '/erro-500' => (function (): void {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Falha inesperada no servidor de teste.']);
    })(),
    '/corpo-invalido' => (function (): void {
        http_response_code(200);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'isto nao e json';
    })(),
    '/sem-conteudo' => (function (): void {
        http_response_code(204);
    })(),
    default => (function (): void {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Rota de teste desconhecida.']);
    })(),
};
