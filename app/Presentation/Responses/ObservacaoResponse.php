<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\ObservacaoDTO;
use PsycheAI\Application\DTOs\ObservacaoResultadoDTO;
use PsycheAI\Application\DTOs\RecorrenciaDTO;

/**
 * Projeta ObservacaoResultadoDTO (Application) para o array que compõe o
 * campo "data" do envelope JSON da API. Nunca inclui hipótese, diagnóstico
 * ou qualquer leitura de sentido — apenas a descrição literal do que se
 * repete (Recorrencia) e o texto registral gerado a partir dela
 * (Observacao), conforme docs/Regras-Dominio.md. `rotuloLacaniano` (Sprint
 * 16) é null a menos que `vocabulario=lacan` tenha sido pedido — quando
 * presente, é sempre uma reclassificação da mesma Recorrencia, nunca um
 * dado novo.
 */
final class ObservacaoResponse
{
    private function __construct(
        private readonly ObservacaoResultadoDTO $dto
    ) {
    }

    public static function fromDTO(ObservacaoResultadoDTO $dto): self
    {
        return new self($dto);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'sujeitoId' => $this->dto->sujeitoId,
            'recorrencias' => array_map(
                static fn (RecorrenciaDTO $recorrencia): array => [
                    'id' => $recorrencia->id,
                    'descricao' => $recorrencia->descricao,
                    'frequencia' => $recorrencia->frequencia,
                    'rotuloLacaniano' => $recorrencia->rotuloLacaniano,
                ],
                $this->dto->recorrencias
            ),
            'observacoes' => array_map(
                static fn (ObservacaoDTO $observacao): array => [
                    'id' => $observacao->id,
                    'texto' => $observacao->texto,
                ],
                $this->dto->observacoes
            ),
        ];
    }
}
