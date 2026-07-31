<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts\DTOs;

/**
 * Contexto conversacional passado a GeradorDePerguntaSocraticaInterface
 * para dar continuidade ao assunto que o Sujeito traz, sem exigir nenhum
 * campo novo no Domínio: $turnosRecentes é montado a partir da paridade
 * de Posicao dentro do Discurso da sessão atual (par = Sujeito, ímpar =
 * Sistema — mesma convenção já usada por MensagemApplicationService e
 * MensagemViewModel).
 */
final class ContextoConversaDTO
{
    /**
     * @param array<int, array{autor: string, conteudo: string}> $turnosRecentes
     *        Turnos recentes da sessão atual, em ordem cronológica.
     * @param bool $ehRepeticao Se a mensagem atual já apareceu antes no
     *        histórico do Sujeito (mesmo critério de DetectorRecorrencias).
     * @param string|null $descricaoRecorrencia Conteúdo normalizado repetido,
     *        quando $ehRepeticao é true.
     */
    public function __construct(
        public readonly array $turnosRecentes,
        public readonly bool $ehRepeticao,
        public readonly ?string $descricaoRecorrencia
    ) {
    }
}
