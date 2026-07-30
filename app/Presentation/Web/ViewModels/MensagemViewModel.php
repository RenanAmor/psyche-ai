<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

/**
 * Projeção somente-leitura de um EventoDiscursivo para a tela de
 * Conversa. O Domínio não guarda quem falou — como o fluxo da Sprint 12
 * sempre alterna estritamente usuário/sistema a cada envio, a posição do
 * evento (par/ímpar) é quem determina o autor exibido, sem exigir nenhum
 * campo novo no Domínio.
 */
final class MensagemViewModel
{
    public function __construct(
        public readonly string $id,
        public readonly string $autor,
        public readonly string $conteudo,
        public readonly int $posicao,
        public readonly string $criadoEm
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        $posicao = (int) ($dados['posicao'] ?? 0);

        return new self(
            id: (string) ($dados['id'] ?? ''),
            autor: $posicao % 2 === 0 ? 'Você' : 'Sistema',
            conteudo: (string) ($dados['conteudo'] ?? ''),
            posicao: $posicao,
            criadoEm: (string) ($dados['criadoEm'] ?? '')
        );
    }

    /**
     * Filtra os eventos da Sessao informada, dentre todos devolvidos por
     * GET /events, e devolve o histórico em ordem cronológica (por
     * Posicao). Evita a necessidade de um endpoint dedicado só para
     * listar mensagens de uma conversa.
     *
     * @param array<int, array<string, mixed>> $eventos
     * @return self[]
     */
    public static function historicoDaSessao(array $eventos, string $sessaoId): array
    {
        $daSessao = array_values(array_filter(
            $eventos,
            static fn (array $evento): bool => ($evento['sessaoId'] ?? null) === $sessaoId
        ));

        usort(
            $daSessao,
            static fn (array $a, array $b): int => ((int) ($a['posicao'] ?? 0)) <=> ((int) ($b['posicao'] ?? 0))
        );

        return array_map(self::fromArray(...), $daSessao);
    }
}
