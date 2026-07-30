<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\CircuitoRecorrenciaDTO;
use PsycheAI\Application\DTOs\CircuitoResultadoDTO;
use PsycheAI\Application\DTOs\OcorrenciaCircuitoDTO;

/**
 * Projeta CircuitoResultadoDTO (Application) para o array que compõe o
 * campo "data" do envelope JSON de GET /subjects/{id}/observations/circuito.
 * Assim como ObservacaoResponse, nunca inclui hipótese, diagnóstico ou
 * leitura de sentido — apenas as mesmas Recorrencias do Motor Freud
 * (`descricao`/`frequencia`), onde/quando cada uma reaparece
 * (`ocorrencias`, uma por Sessão/Discurso/Evento) e, opcionalmente, o
 * rótulo lacaniano ao lado (`rotuloLacaniano`, null a menos que
 * `vocabulario=lacan` tenha sido pedido).
 */
final class CircuitoResponse
{
    private function __construct(
        private readonly CircuitoResultadoDTO $dto
    ) {
    }

    public static function fromDTO(CircuitoResultadoDTO $dto): self
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
            'circuitos' => array_map(
                static fn (CircuitoRecorrenciaDTO $circuito): array => [
                    'id' => $circuito->id,
                    'descricao' => $circuito->descricao,
                    'frequencia' => $circuito->frequencia,
                    'rotuloLacaniano' => $circuito->rotuloLacaniano,
                    'ocorrencias' => array_map(
                        static fn (OcorrenciaCircuitoDTO $ocorrencia): array => [
                            'sessaoId' => $ocorrencia->sessaoId,
                            'discursoId' => $ocorrencia->discursoId,
                            'eventoId' => $ocorrencia->eventoId,
                            'momento' => $ocorrencia->momento,
                            'posicao' => $ocorrencia->posicao,
                        ],
                        $circuito->ocorrencias
                    ),
                ],
                $this->dto->circuitos
            ),
        ];
    }
}
