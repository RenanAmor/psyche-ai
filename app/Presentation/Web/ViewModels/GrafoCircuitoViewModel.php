<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

/**
 * Projeção do circuito/trajeto (revisão pós-Sprint 16) como grafo — nós =
 * Sessões distintas, arestas = elo entre duas ocorrências consecutivas de
 * uma mesma Recorrencia (Sprint 19, "Camada de Visualização Gráfica").
 * Não é a cadeia de significantes lacaniana (Ontologia-Lacan.md §4 não
 * define nenhuma representação computacional para ela ainda) — apenas uma
 * nova serialização do mesmo dado Freud-side que `CircuitoTrajetoComponent`
 * já exibe como texto, para consumo por `fetch()`/D3 no navegador.
 */
final class GrafoCircuitoViewModel
{
    /**
     * @param array<int, array{id: string, momento: string}> $nos
     * @param array<int, array{origem: string, destino: string, recorrenciaId: string, descricao: string, rotuloLacaniano: ?string}> $arestas
     */
    public function __construct(
        public readonly array $nos,
        public readonly array $arestas
    ) {
    }

    /**
     * @param CircuitoRecorrenciaViewModel[] $circuitos
     */
    public static function apartirDosCircuitos(array $circuitos): self
    {
        $nos = [];
        $arestas = [];

        foreach ($circuitos as $circuito) {
            foreach ($circuito->ocorrencias as $indice => $ocorrencia) {
                $nos[$ocorrencia->sessaoId] ??= [
                    'id' => $ocorrencia->sessaoId,
                    'momento' => $ocorrencia->momento,
                ];

                if ($indice === 0) {
                    continue;
                }

                $anterior = $circuito->ocorrencias[$indice - 1];

                $arestas[] = [
                    'origem' => $anterior->sessaoId,
                    'destino' => $ocorrencia->sessaoId,
                    'recorrenciaId' => $circuito->id,
                    'descricao' => $circuito->descricao,
                    'rotuloLacaniano' => $circuito->rotuloLacaniano,
                ];
            }
        }

        return new self(nos: array_values($nos), arestas: $arestas);
    }

    /**
     * @return array{nos: array<int, array{id: string, momento: string}>, arestas: array<int, array{origem: string, destino: string, recorrenciaId: string, descricao: string, rotuloLacaniano: ?string}>}
     */
    public function toArray(): array
    {
        return [
            'nos' => $this->nos,
            'arestas' => $this->arestas,
        ];
    }
}
