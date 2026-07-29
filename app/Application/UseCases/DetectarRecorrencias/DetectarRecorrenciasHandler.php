<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\DetectarRecorrencias;

use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;
use PsycheAI\Domain\Entities\Recorrencia;
use PsycheAI\Domain\Services\DetectorRecorrencias;
use PsycheAI\Domain\Specifications\RecorrenciaMinimaSpecification;
use PsycheAI\Domain\ValueObjects\Frequencia;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Texto;

final class DetectarRecorrenciasHandler implements UseCaseInterface
{
    public function __construct(
        private readonly DetectorRecorrencias $detectorRecorrencias = new DetectorRecorrencias()
    ) {
    }

    public function handle(DetectarRecorrenciasCommand $command): DetectarRecorrenciasResult
    {
        $eventos = $this->coletarEventos($command->memoria);

        $contagemPorConteudo = $this->detectorRecorrencias->detectar($eventos);

        $especificacaoMinima = $command->minimo === null
            ? new RecorrenciaMinimaSpecification()
            : new RecorrenciaMinimaSpecification($command->minimo);

        $recorrencias = [];
        $indice = 0;

        foreach ($contagemPorConteudo as $conteudo => $contagem) {
            $recorrencia = new Recorrencia(
                new Identificador((string) (++$indice)),
                new Texto($conteudo),
                new Frequencia($contagem)
            );

            if ($especificacaoMinima->isSatisfiedBy($recorrencia)) {
                $recorrencias[] = $recorrencia;
            }
        }

        return new DetectarRecorrenciasResult($recorrencias);
    }

    /**
     * @return EventoDiscursivo[]
     */
    private function coletarEventos(MemoriaLongitudinal $memoria): array
    {
        $eventos = [];

        foreach ($memoria->sessoes() as $sessao) {
            foreach ($sessao->discursos() as $discurso) {
                foreach ($discurso->eventos() as $evento) {
                    $eventos[] = $evento;
                }
            }
        }

        return $eventos;
    }
}
