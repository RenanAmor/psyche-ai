<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

use PsycheAI\Infrastructure\Contracts\DTOs\ContextoConversaDTO;

/**
 * Porta para geração da pergunta socrática dinâmica devolvida ao Sujeito
 * (Documento-Mestre.md §6.7 — "o PsycheAI não fala como quem sabe, fala
 * como quem pergunta"; Regras 7/9/10/11, docs/Regras-Dominio.md). Quem
 * implementa decide como gerar (LLM, regra determinística, etc.) — a
 * Application nunca conhece a implementação concreta, só este contrato.
 *
 * Implementações devem devolver null em qualquer situação de incerteza
 * (saída fora do formato esperado, erro de rede/API, resposta que não
 * configura uma pergunta) em vez de lançar exceção — o chamador trata
 * null exatamente como "use o fallback determinístico", nunca deixa
 * texto fora de formato chegar ao Sujeito.
 */
interface GeradorDePerguntaSocraticaInterface
{
    public function gerar(string $mensagemUsuario, ContextoConversaDTO $contexto): ?string;
}
