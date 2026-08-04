<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

/**
 * Projeção somente-leitura de um EventoDiscursivo para a tela de
 * Conversa. Desde a Videochamada Embutida, o Domínio guarda quem falou
 * explicitamente (Locutor) — esta classe só traduz o valor bruto
 * ('sujeito'/'sistema'/'analista') para o rótulo exibido, sem inferir
 * nada pela posição (a antiga heurística de paridade já falhava em
 * silêncio para transcrição de áudio com múltiplos segmentos).
 */
final class MensagemViewModel
{
    private const ROTULOS_POR_LOCUTOR = [
        'sujeito' => 'Você',
        'sistema' => 'Sistema',
        'analista' => 'Analista',
    ];

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
        $locutor = (string) ($dados['locutor'] ?? 'desconhecido');

        return new self(
            id: (string) ($dados['id'] ?? ''),
            autor: self::ROTULOS_POR_LOCUTOR[$locutor] ?? 'Desconhecido',
            conteudo: (string) ($dados['conteudo'] ?? ''),
            posicao: (int) ($dados['posicao'] ?? 0),
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
