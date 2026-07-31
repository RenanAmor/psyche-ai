<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\AI;

use PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal\ConstruirMemoriaLongitudinalCommand;
use PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal\ConstruirMemoriaLongitudinalHandler;
use PsycheAI\Application\UseCases\DetectarRecorrencias\DetectarRecorrenciasCommand;
use PsycheAI\Application\UseCases\DetectarRecorrencias\DetectarRecorrenciasHandler;
use PsycheAI\Application\UseCases\GerarPerguntaSocratica\GerarPerguntaSocraticaCommand;
use PsycheAI\Application\UseCases\GerarPerguntaSocratica\GerarPerguntaSocraticaHandler;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Repositories\SessaoRepository;
use PsycheAI\Domain\Repositories\SujeitoRepository;
use PsycheAI\Domain\Services\DetectorRecorrencias;
use PsycheAI\Infrastructure\Contracts\DTOs\ContextoConversaDTO;
use PsycheAI\Infrastructure\Contracts\RespostaAutomaticaInterface;

/**
 * Implementação de RespostaAutomaticaInterface que passa a valer como
 * binding padrão a partir da Sprint 23 (Motor de Enunciação Socrática):
 * substitui o template fixo/eco da Sprint 17 por uma pergunta gerada
 * dinamicamente via LLM (GerarPerguntaSocraticaHandler), dando
 * continuidade real ao assunto trazido pelo Sujeito em toda mensagem —
 * não só quando uma repetição literal é detectada (decisão do usuário).
 *
 * A resposta determinística de RespostaEcoRecorrenciaService (Sprint 17)
 * continua existindo como rede de segurança: é calculada primeiro e
 * devolvida sempre que o Sujeito não é encontrado, ou sempre que o LLM
 * falha/devolve algo fora do formato esperado (GerarPerguntaSocraticaHandler
 * devolve null nesses casos) — nunca deixa a conversa quebrar por causa
 * de uma falha de rede/API.
 *
 * $ehRepeticao/$descricaoRecorrencia são recalculados aqui com o mesmo
 * critério de RespostaEcoRecorrenciaService (ConstruirMemoriaLongitudinal +
 * DetectarRecorrencias, minimo=1) só para enriquecer o contexto passado ao
 * LLM — nunca para decidir se o LLM é chamado, e nunca expõem vocabulário
 * técnico ao Sujeito (isso é validado no próprio prompt/guardrail de
 * GeradorDePerguntaSocraticaLLM).
 */
final class RespostaSocraticaService implements RespostaAutomaticaInterface
{
    private const TURNOS_RECENTES_MAXIMO = 8;

    public function __construct(
        private readonly SujeitoRepository $sujeitoRepository,
        private readonly SessaoRepository $sessaoRepository,
        private readonly GerarPerguntaSocraticaHandler $gerarPerguntaSocratica,
        private readonly RespostaEcoRecorrenciaService $respostaFallback,
        private readonly ConstruirMemoriaLongitudinalHandler $construirMemoriaLongitudinal = new ConstruirMemoriaLongitudinalHandler(),
        private readonly DetectarRecorrenciasHandler $detectarRecorrencias = new DetectarRecorrenciasHandler()
    ) {
    }

    public function responder(string $mensagemUsuario, string $sujeitoId = '', string $sessaoId = ''): string
    {
        $respostaDeSeguranca = $this->respostaFallback->responder($mensagemUsuario, $sujeitoId, $sessaoId);

        $sujeito = $sujeitoId === '' ? null : $this->sujeitoRepository->findById($sujeitoId);

        if ($sujeito === null) {
            return $respostaDeSeguranca;
        }

        $memoria = $this->construirMemoriaLongitudinal
            ->handle(new ConstruirMemoriaLongitudinalCommand($sujeito, $sujeitoId))
            ->memoria();

        $recorrencias = $this->detectarRecorrencias
            ->handle(new DetectarRecorrenciasCommand($memoria, minimo: 1))
            ->recorrencias();

        $normalizado = DetectorRecorrencias::normalizar($mensagemUsuario);

        $descricaoRecorrencia = null;

        foreach ($recorrencias as $recorrencia) {
            if ($recorrencia->descricao()->valor() === $normalizado) {
                $descricaoRecorrencia = $recorrencia->descricao()->valor();
                break;
            }
        }

        $contexto = new ContextoConversaDTO(
            turnosRecentes: $this->turnosRecentesDaSessao($sessaoId),
            ehRepeticao: $descricaoRecorrencia !== null,
            descricaoRecorrencia: $descricaoRecorrencia
        );

        $pergunta = $this->gerarPerguntaSocratica
            ->handle(new GerarPerguntaSocraticaCommand($mensagemUsuario, $contexto))
            ->pergunta();

        return $pergunta ?? $respostaDeSeguranca;
    }

    /**
     * @return array<int, array{autor: string, conteudo: string}>
     */
    private function turnosRecentesDaSessao(string $sessaoId): array
    {
        $sessao = $sessaoId === '' ? null : $this->sessaoRepository->findById($sessaoId);
        $discurso = $sessao?->discursos()[0] ?? null;

        if ($discurso === null) {
            return [];
        }

        $eventos = $discurso->eventos();

        usort(
            $eventos,
            static fn (EventoDiscursivo $a, EventoDiscursivo $b): int => $a->posicao()->valor() <=> $b->posicao()->valor()
        );

        $recentes = array_slice($eventos, -self::TURNOS_RECENTES_MAXIMO);

        return array_map(
            static fn (EventoDiscursivo $evento): array => [
                'autor' => $evento->posicao()->valor() % 2 === 0 ? 'Sujeito' : 'Sistema',
                'conteudo' => $evento->conteudo()->valor(),
            ],
            $recentes
        );
    }
}
