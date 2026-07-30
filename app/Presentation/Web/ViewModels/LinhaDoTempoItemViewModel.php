<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

/**
 * Projeção somente-leitura de um item da Linha do Tempo Discursiva
 * (Sessao, Discurso, EventoDiscursivo ou MemoriaLongitudinal) para a
 * tela de Histórico do Sujeito. Apenas organiza o que a API já devolveu
 * — o rótulo e o resumo são descritivos da estrutura (tipo, contagens),
 * nunca uma leitura sobre o conteúdo do discurso.
 */
final class LinhaDoTempoItemViewModel
{
    private const ROTULOS = [
        'sessao' => 'Sessão',
        'discurso' => 'Discurso',
        'evento' => 'Evento Discursivo',
        'memoria' => 'Memória Longitudinal',
    ];

    /**
     * @param array<string, mixed> $dados
     */
    public function __construct(
        public readonly string $tipo,
        public readonly string $id,
        public readonly string $timestamp,
        public readonly array $dados
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            tipo: (string) ($dados['tipo'] ?? ''),
            id: (string) ($dados['id'] ?? ''),
            timestamp: (string) ($dados['timestamp'] ?? ''),
            dados: is_array($dados['dados'] ?? null) ? $dados['dados'] : []
        );
    }

    /**
     * @param array<int, array<string, mixed>> $lista
     * @return self[]
     */
    public static function fromArrayList(array $lista): array
    {
        return array_map(self::fromArray(...), $lista);
    }

    public function rotulo(): string
    {
        return self::ROTULOS[$this->tipo] ?? ucfirst($this->tipo);
    }

    public function resumo(): string
    {
        return match ($this->tipo) {
            'sessao' => sprintf('%d discurso(s) registrado(s).', (int) ($this->dados['quantidadeDeDiscursos'] ?? 0)),
            'discurso' => (string) ($this->dados['conteudo'] ?? ''),
            'evento' => (string) ($this->dados['conteudo'] ?? ''),
            'memoria' => sprintf('Consolida %d sessão(ões).', (int) ($this->dados['quantidadeDeSessoes'] ?? 0)),
            default => '',
        };
    }

    /**
     * Rota de detalhe do recurso subjacente, quando existir uma tela
     * própria de "mostrar" para o tipo (Sessao, Discurso, Memória).
     * EventoDiscursivo não tem tela de detalhe própria — apenas listagem
     * — por isso não recebe link direto.
     */
    public function rotaDetalhe(): ?string
    {
        return match ($this->tipo) {
            'sessao' => '/sessoes/' . $this->id,
            'discurso' => '/discursos/' . $this->id,
            'memoria' => '/memorias/' . $this->id,
            default => null,
        };
    }
}
