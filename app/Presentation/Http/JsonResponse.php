<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Http;

/**
 * Padroniza o envelope JSON de toda a API:
 * sucesso -> {"success": true, "data": ...}
 * erro    -> {"success": false, "message": "...", "errors": []}
 */
final class JsonResponse extends Response
{
    /**
     * @param array<string, mixed> $payload
     */
    private function __construct(int $status, array $payload)
    {
        parent::__construct(
            $status,
            $payload === [] ? '' : (string) json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ['Content-Type' => 'application/json; charset=utf-8']
        );
    }

    public static function sucesso(mixed $data, int $status = 200): self
    {
        return new self($status, ['success' => true, 'data' => $data]);
    }

    /**
     * @param string[] $erros
     */
    public static function erro(string $mensagem, int $status, array $erros = []): self
    {
        return new self($status, ['success' => false, 'message' => $mensagem, 'errors' => $erros]);
    }

    public static function semConteudo(): self
    {
        return new self(204, []);
    }
}
