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
use PsycheAI\Application\UseCases\ClassificarFormacaoFreudiana\ClassificarFormacaoFreudianaCommand;
use PsycheAI\Application\UseCases\ClassificarFormacaoFreudiana\ClassificarFormacaoFreudianaHandler;
use PsycheAI\Application\UseCases\DetectarCircuitoRecorrencia\DetectarCircuitoRecorrenciaCommand;
use PsycheAI\Application\UseCases\DetectarCircuitoRecorrencia\DetectarCircuitoRecorrenciaHandler;
use PsycheAI\Domain\Entities\Observacao;
use PsycheAI\Domain\Entities\Recorrencia;
use PsycheAI\Domain\Repositories\SujeitoRepository;
use PsycheAI\Domain\Services\ReclassificadorLacaniano;
use PsycheAI\Domain\ValueObjects\OcorrenciaRecorrencia;
use PsycheAI\Domain\ValueObjects\TipoFormacaoFreudiana;

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
 *
 * Fundamentação teórica (Regra 11, Regras-Dominio.md): quando
 * $comLeituraLacaniana é true, cada Recorrencia recebe também a regra
 * da ontologia que fundamenta seu rótulo
 * (ReclassificadorLacaniano::fundamentacaoPara()) — exclusivo das telas
 * do analista, nunca da conversa do sujeito. Regra de precedência para
 * o rótulo único (rotularComFundamentacao()): circuito (≥2 sessões) tem
 * prioridade e não chama o Motor Freud/LLM (a leitura de circuito já é
 * a mais rica disponível); senão, classifica o conteúdo via
 * ClassificarFormacaoFreudianaHandler (Motor Freud/LLM) e reclassifica
 * com reclassificarPorTipoFreudiano(); sem classificador disponível (ou
 * NaoClassificado), cai no rótulo padrão de sempre.
 */
final class ObservacaoApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly SujeitoRepository $sujeitoRepository,
        private readonly CicloDeObservacaoService $cicloDeObservacao = new CicloDeObservacaoService(),
        private readonly ReclassificadorLacaniano $reclassificadorLacaniano = new ReclassificadorLacaniano(),
        private readonly DetectarCircuitoRecorrenciaHandler $detectarCircuitoRecorrencia = new DetectarCircuitoRecorrenciaHandler(),
        private readonly ?ClassificarFormacaoFreudianaHandler $classificarFormacaoFreudiana = null
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

        return new CircuitoResultadoDTO(
            sujeitoId: $sujeitoId,
            circuitos: array_map(
                function (Recorrencia $recorrencia) use ($circuitosPorRecorrencia, $comLeituraLacaniana): CircuitoRecorrenciaDTO {
                    $ocorrencias = $circuitosPorRecorrencia[$recorrencia->id()->valor()] ?? [];

                    [$rotulo, $fundamentacao] = $comLeituraLacaniana
                        ? $this->rotularComFundamentacao($recorrencia, $ocorrencias)
                        : [null, null];

                    return CircuitoRecorrenciaDTO::fromRecorrencia($recorrencia, $ocorrencias, $rotulo, $fundamentacao);
                },
                $resultado->recorrencias()
            )
        );
    }

    /**
     * Regra de precedência para o rótulo único (ver docblock da classe):
     * circuito primeiro (não chama o Motor Freud/LLM), senão classifica
     * via LLM e reclassifica com vocabulário lacaniano, senão cai no
     * rótulo padrão.
     *
     * @param OcorrenciaRecorrencia[] $ocorrencias
     * @return array{0: string, 1: string}
     */
    private function rotularComFundamentacao(Recorrencia $recorrencia, array $ocorrencias): array
    {
        $sessoesDistintas = array_unique(array_map(
            static fn (OcorrenciaRecorrencia $ocorrencia): string => $ocorrencia->sessaoId(),
            $ocorrencias
        ));

        if (count($sessoesDistintas) >= 2) {
            $id = $recorrencia->id()->valor();
            $rotulo = $this->reclassificadorLacaniano
                ->reclassificarComTrajeto([$recorrencia], [$id => $ocorrencias])[$id];

            return [$rotulo, $this->reclassificadorLacaniano->fundamentacaoPara($rotulo)];
        }

        $tipo = $this->classificarFormacaoFreudiana !== null
            ? $this->classificarFormacaoFreudiana
                ->handle(new ClassificarFormacaoFreudianaCommand($recorrencia->descricao()->valor()))
                ->tipo()
            : TipoFormacaoFreudiana::NaoClassificado;

        $rotulo = $this->reclassificadorLacaniano->reclassificarPorTipoFreudiano($tipo);

        return [$rotulo, $this->reclassificadorLacaniano->fundamentacaoPara($rotulo)];
    }
}
