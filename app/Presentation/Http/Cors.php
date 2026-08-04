<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Http;

/**
 * Suporte a CORS para chamadas de navegador direto na API (ex.: formulário
 * estático de ecopsy.online fazendo fetch() para POST /waitlist) — até
 * então a API só era chamada servidor-a-servidor (ApiHttpClient da Web),
 * então nenhum cabeçalho Access-Control-* existia. Lista de permissão
 * explícita (nunca "*") porque a origem devolvida em
 * Access-Control-Allow-Origin também autoriza credenciais implícitas do
 * navegador (cookies) em requisições futuras.
 */
final class Cors
{
    /**
     * @param string[] $origensPermitidas
     */
    public function __construct(
        private readonly array $origensPermitidas
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function cabecalhosPara(?string $origem): array
    {
        if ($origem === null || !in_array($origem, $this->origensPermitidas, true)) {
            return [];
        }

        return [
            'Access-Control-Allow-Origin' => $origem,
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type',
        ];
    }

    public function ehPreflight(Request $request): bool
    {
        return $request->metodo() === 'OPTIONS';
    }
}
