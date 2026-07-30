<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\CircuitoRecorrenciaDTO;
use PsycheAI\Application\DTOs\CircuitoResultadoDTO;
use PsycheAI\Application\DTOs\ObservacaoDTO;
use PsycheAI\Application\DTOs\ObservacaoResultadoDTO;
use PsycheAI\Application\DTOs\RecorrenciaDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\UseCases\DetectarCircuitoRecorrencia\DetectarCircuitoRecorrenciaCommand;
use PsycheAI\Application\UseCases\DetectarCircuitoRecorrencia\DetectarCircuitoRecorrenciaHandler;
use PsycheAI\Domain\Entities\Observacao;
use PsycheAI\Domain\Entities\Recorrencia;
use PsycheAI\Domain\Repositories\SujeitoRepository;
use PsycheAI\Domain\Services\ReclassificadorLacaniano;

/**
 * Expõe o Discourse Engine (Sprint 14) e o Motor Freud (Sprint 15): recalcula,
 * a cada consulta e sem persistir nada, as Recorrências e Observações de um
 * Sujeito a partir do ciclo já existente em CicloDeObservacaoService — mesmo
 * padrão de LinhaDoTempoApplicationService/ConsolidacaoApplicationService
 * (Sprint 13), que também nunca gravam o resultado derivado no banco.
 *
 * A MemoriaLongitudinal montada aqui é transitória: existe apenas durante
 * esta chamada, por isso usa o próprio id do Sujeito como identificador,
 * sem risco de colisão com nenhuma MemoriaLongitudinal persistida.
 *
 * Quando $comLeituraLacaniana é true, o Motor Lacan (Sprint 16) reclassifica
 * as mesmas Recorrencias com vocabulário lacaniano (ReclassificadorLacaniano)
 * — nenhum dado novo é produzido, apenas um rótulo ao lado do que o Motor
 * Freud já trouxe.
 *
 * Desde a revisão pós-Sprint 16, consultarCircuito() expõe o "mapear a
 * pulsão, todo o caminho": usa o mesmo resultado já filtrado (limiar ≥2)
 * de CicloDeObservacaoService::executar() como única fonte de quais
 * Recorrencias existem, cruzando com
 * DetectorRecorrencias::detectarCircuito() (via
 * DetectarCircuitoRecorrenciaHandler) para expor quando/onde cada uma
 * reaparece através das Sessões — sem introduzir nenhuma Recorrencia que
 * o Motor Freud não tenha trazido.
 */
final class ObservacaoApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly SujeitoRepository $sujeitoRepository,
        private readonly CicloDeObservacaoService $cicloDeObservacao = new CicloDeObservacaoService(),
        private readonly ReclassificadorLacaniano $reclassificadorLacaniano = new ReclassificadorLacaniano(),
        private readonly DetectarCircuitoRecorrenciaHandler $detectarCircuitoRecorrencia = new DetectarCircuitoRecorrenciaHandler()
    ) {
    }

    public function consultar(
        string $sujeitoId,
        ?int $minimoDeRecorrencia = null,
        bool $comLeituraLacaniana = false
    ): ObservacaoResultadoDTO {
        $sujeito = $this->sujeitoRepository->findById($sujeitoId);

        if ($sujeito === null) {
            throw RecursoNaoEncontradoException::paraId('Sujeito', $sujeitoId);
        }

        $resultado = $this->cicloDeObservacao->executar($sujeito, $sujeitoId, $minimoDeRecorrencia);

        $rotulosLacanianos = $comLeituraLacaniana
            ? $this->reclassificadorLacaniano->reclassificar($resultado->recorrencias())
            : [];

        return new ObservacaoResultadoDTO(
            sujeitoId: $sujeitoId,
            recorrencias: array_map(
                static fn (Recorrencia $recorrencia): RecorrenciaDTO => RecorrenciaDTO::fromEntity(
                    $recorrencia,
                    $rotulosLacanianos[$recorrencia->id()->valor()] ?? null
                ),
                $resultado->recorrencias()
            ),
            observacoes: array_map(
                static fn (Observacao $observacao): ObservacaoDTO => ObservacaoDTO::fromEntity($observacao),
                $resultado->observacoes()
            )
        );
    }

    public function consultarCircuito(
        string $sujeitoId,
        ?int $minimoDeRecorrencia = null,
        bool $comLeituraLacaniana = false
    ): CircuitoResultadoDTO {
        $sujeito = $this->sujeitoRepository->findById($sujeitoId);

        if ($sujeito === null) {
            throw RecursoNaoEncontradoException::paraId('Sujeito', $sujeitoId);
        }

        $resultado = $this->cicloDeObservacao->executar($sujeito, $sujeitoId, $minimoDeRecorrencia);

        $circuitosPorRecorrencia = $this->detectarCircuitoRecorrencia
            ->handle(new DetectarCircuitoRecorrenciaCommand($resultado->memoria(), $resultado->recorrencias()))
            ->circuitosPorRecorrencia();

        $rotulosLacanianos = $comLeituraLacaniana
            ? $this->reclassificadorLacaniano->reclassificarComTrajeto($resultado->recorrencias(), $circuitosPorRecorrencia)
            : [];

        return new CircuitoResultadoDTO(
            sujeitoId: $sujeitoId,
            circuitos: array_map(
                static fn (Recorrencia $recorrencia): CircuitoRecorrenciaDTO => CircuitoRecorrenciaDTO::fromRecorrencia(
                    $recorrencia,
                    $circuitosPorRecorrencia[$recorrencia->id()->valor()] ?? [],
                    $rotulosLacanianos[$recorrencia->id()->valor()] ?? null
                ),
                $resultado->recorrencias()
            )
        );
    }
}
