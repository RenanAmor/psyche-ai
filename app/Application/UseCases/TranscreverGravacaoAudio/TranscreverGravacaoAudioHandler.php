<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\TranscreverGravacaoAudio;

use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Application\UseCases\RegistrarEventoDiscursivo\RegistrarEventoDiscursivoCommand;
use PsycheAI\Application\UseCases\RegistrarEventoDiscursivo\RegistrarEventoDiscursivoHandler;

/**
 * Divide a fala contínua de uma GravacaoAudio (Sprint 22) nos mesmos
 * EventoDiscursivo que uma mensagem digitada já produz — reaproveita
 * RegistrarEventoDiscursivoHandler, o mesmo usado por
 * MensagemApplicationService::enviar(), para garantir que texto falado e
 * texto digitado sejam tratados de forma idêntica pelo resto do sistema
 * (Motores Freud/Lacan, Memória Longitudinal). Não dispara nenhuma
 * resposta automática: a gravação é a fala do sujeito, não um turno de
 * pergunta/resposta.
 */
final class TranscreverGravacaoAudioHandler implements UseCaseInterface
{
    public function __construct(
        private readonly RegistrarEventoDiscursivoHandler $registrarEvento = new RegistrarEventoDiscursivoHandler()
    ) {
    }

    public function handle(TranscreverGravacaoAudioCommand $command): TranscreverGravacaoAudioResult
    {
        $posicaoInicial = count($command->discurso->eventos());
        $eventos = [];

        foreach (array_values($command->segmentos) as $indice => $segmento) {
            $eventos[] = $this->registrarEvento
                ->handle(new RegistrarEventoDiscursivoCommand(
                    $command->discurso,
                    $segmento['id'],
                    $segmento['texto'],
                    $posicaoInicial + $indice
                ))
                ->evento();
        }

        $command->gravacao->marcarTranscrita();

        return new TranscreverGravacaoAudioResult($eventos, $command->gravacao);
    }
}
