<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

/**
 * Projeção somente-leitura do circuito/trajeto de uma Recorrencia
 * (revisão pós-Sprint 16, "mapear a pulsão, todo o caminho"): a mesma
 * Recorrencia do Motor Freud, mais a sequência cronológica de Sessões em
 * que reaparece — nunca uma leitura sobre o que esse retorno significa.
 */
final class CircuitoRecorrenciaViewModel
{
    /**
     * @param OcorrenciaCircuitoViewModel[] $ocorrencias
     */
    public function __construct(
        public readonly string $id,
        public readonly string $descricao,
        public readonly int $frequencia,
        public readonly array $ocorrencias,
        public readonly ?string $rotuloLacaniano = null,
        public readonly ?string $fundamentacaoTeorica = null
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            id: (string) ($dados['id'] ?? ''),
            descricao: (string) ($dados['descricao'] ?? ''),
            frequencia: (int) ($dados['frequencia'] ?? 0),
            ocorrencias: OcorrenciaCircuitoViewModel::fromArrayList(
                is_array($dados['ocorrencias'] ?? null) ? $dados['ocorrencias'] : []
            ),
            rotuloLacaniano: isset($dados['rotuloLacaniano']) ? (string) $dados['rotuloLacaniano'] : null,
            fundamentacaoTeorica: isset($dados['fundamentacaoTeorica']) ? (string) $dados['fundamentacaoTeorica'] : null
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

    /**
     * "Sessão {data} → Sessão {data} → …": trajeto textual literal das
     * ocorrências, na mesma ordem cronológica em que a API já as devolve
     * — nenhuma reordenação ou seleção acontece aqui.
     */
    public function trajeto(): string
    {
        return implode(' → ', array_map(
            static fn (OcorrenciaCircuitoViewModel $ocorrencia): string => 'Sessão ' . $ocorrencia->momento,
            $this->ocorrencias
        ));
    }
}
