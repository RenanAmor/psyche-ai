<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal\ConstruirMemoriaLongitudinalCommand;
use PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal\ConstruirMemoriaLongitudinalHandler;
use PsycheAI\Application\UseCases\DetectarRecorrencias\DetectarRecorrenciasCommand;
use PsycheAI\Application\UseCases\DetectarRecorrencias\DetectarRecorrenciasHandler;
use PsycheAI\Application\UseCases\GerarObservacoes\GerarObservacoesCommand;
use PsycheAI\Application\UseCases\GerarObservacoes\GerarObservacoesHandler;
use PsycheAI\Domain\Entities\Sujeito;

/**
 * Coordena, em sequência, os três últimos estágios do ciclo do sistema
 * descritos em docs/Ciclo-do-Sistema.md: construção da memória
 * longitudinal, identificação de recorrências e produção de observações.
 *
 * Não introduz nenhuma regra de negócio nova: apenas encadeia os Use Cases
 * já existentes.
 */
final class CicloDeObservacaoService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly ConstruirMemoriaLongitudinalHandler $construirMemoriaLongitudinal = new ConstruirMemoriaLongitudinalHandler(),
        private readonly DetectarRecorrenciasHandler $detectarRecorrencias = new DetectarRecorrenciasHandler(),
        private readonly GerarObservacoesHandler $gerarObservacoes = new GerarObservacoesHandler()
    ) {
    }

    public function executar(Sujeito $sujeito, string $memoriaId, ?int $minimoDeRecorrencia = null): CicloDeObservacaoResultado
    {
        $memoria = $this->construirMemoriaLongitudinal
            ->handle(new ConstruirMemoriaLongitudinalCommand($sujeito, $memoriaId))
            ->memoria();

        $recorrencias = $this->detectarRecorrencias
            ->handle(new DetectarRecorrenciasCommand($memoria, $minimoDeRecorrencia))
            ->recorrencias();

        $observacoes = $this->gerarObservacoes
            ->handle(new GerarObservacoesCommand($recorrencias))
            ->observacoes();

        return new CicloDeObservacaoResultado($memoria, $recorrencias, $observacoes);
    }
}
