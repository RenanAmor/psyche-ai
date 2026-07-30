<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\ObservacaoDTO;
use PsycheAI\Application\DTOs\ObservacaoResultadoDTO;
use PsycheAI\Application\DTOs\RecorrenciaDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
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
 */
final class ObservacaoApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly SujeitoRepository $sujeitoRepository,
        private readonly CicloDeObservacaoService $cicloDeObservacao = new CicloDeObservacaoService(),
        private readonly ReclassificadorLacaniano $reclassificadorLacaniano = new ReclassificadorLacaniano()
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
}
