<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\AI;

use PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal\ConstruirMemoriaLongitudinalCommand;
use PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal\ConstruirMemoriaLongitudinalHandler;
use PsycheAI\Application\UseCases\DetectarRecorrencias\DetectarRecorrenciasCommand;
use PsycheAI\Application\UseCases\DetectarRecorrencias\DetectarRecorrenciasHandler;
use PsycheAI\Domain\Repositories\SujeitoRepository;
use PsycheAI\Domain\Services\DetectorRecorrencias;
use PsycheAI\Infrastructure\Contracts\RespostaAutomaticaInterface;

/**
 * Implementação de RespostaAutomaticaInterface que passa a valer como
 * binding padrão a partir da Sprint 17 (Interface Conversacional):
 * quando o conteúdo normalizado da mensagem que o usuário acabou de
 * enviar já apareceu ao menos uma vez no histórico persistido do
 * Sujeito, a resposta automática é uma pergunta-eco que só nomeia a
 * repetição e convida a continuar falando — nunca uma afirmação, nunca
 * uma hipótese sobre a causa da repetição (Regra 7,
 * docs/Regras-Dominio.md; "atenção flutuante" de Ontologia-Freud.md).
 *
 * Reaproveita os mesmos Use Cases que ObservacaoApplicationService usa
 * para o Discourse Engine (ConstruirMemoriaLongitudinal +
 * DetectarRecorrencias) em vez de duplicar a regra de "o que conta como
 * repetição". Chama DetectarRecorrenciasCommand com minimo=1 (não o
 * padrão 2 usado pela tela de Observações) porque aqui o que importa é
 * saber se a mensagem atual, ainda não persistida, já foi dita ANTES
 * pelo menos uma vez — ou seja, se ela é a própria segunda ocorrência.
 *
 * Quando não há repetição (ou o Sujeito não é encontrado), delega para
 * RespostaFixaService — a mesma resposta neutra de antes da Sprint 17.
 */
final class RespostaEcoRecorrenciaService implements RespostaAutomaticaInterface
{
    public function __construct(
        private readonly SujeitoRepository $sujeitoRepository,
        private readonly RespostaFixaService $respostaPadrao = new RespostaFixaService(),
        private readonly ConstruirMemoriaLongitudinalHandler $construirMemoriaLongitudinal = new ConstruirMemoriaLongitudinalHandler(),
        private readonly DetectarRecorrenciasHandler $detectarRecorrencias = new DetectarRecorrenciasHandler()
    ) {
    }

    public function responder(string $mensagemUsuario, string $sujeitoId = '', string $sessaoId = ''): string
    {
        $sujeito = $sujeitoId === '' ? null : $this->sujeitoRepository->findById($sujeitoId);

        if ($sujeito === null) {
            return $this->respostaPadrao->responder($mensagemUsuario, $sujeitoId, $sessaoId);
        }

        $memoria = $this->construirMemoriaLongitudinal
            ->handle(new ConstruirMemoriaLongitudinalCommand($sujeito, $sujeitoId))
            ->memoria();

        $recorrencias = $this->detectarRecorrencias
            ->handle(new DetectarRecorrenciasCommand($memoria, minimo: 1))
            ->recorrencias();

        $normalizado = DetectorRecorrencias::normalizar($mensagemUsuario);

        foreach ($recorrencias as $recorrencia) {
            if ($recorrencia->descricao()->valor() === $normalizado) {
                return sprintf(
                    'Você voltou a falar em "%s". O que vem à mente sobre isso?',
                    trim($mensagemUsuario)
                );
            }
        }

        return $this->respostaPadrao->responder($mensagemUsuario, $sujeitoId, $sessaoId);
    }
}
