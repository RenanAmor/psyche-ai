<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\ConsolidacaoMemoriaDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Domain\Repositories\MemoriaRepository;
use PsycheAI\Domain\Repositories\SessaoRepository;
use PsycheAI\Domain\Repositories\SujeitoRepository;

/**
 * Consolida automaticamente a produção discursiva de um Sujeito em
 * contagens simples — quantidade de Sessões, Discursos, Eventos
 * Discursivos e Memórias Longitudinais. Não compara sessões, não
 * identifica recorrências e não produz nenhuma leitura sobre o
 * conteúdo: apenas soma o que já foi registrado.
 */
final class ConsolidacaoApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly SujeitoRepository $sujeitoRepository,
        private readonly MemoriaRepository $memoriaRepository,
        private readonly SessaoRepository $sessaoRepository
    ) {
    }

    public function consolidar(string $sujeitoId): ConsolidacaoMemoriaDTO
    {
        $sujeito = $this->sujeitoRepository->findById($sujeitoId);

        if ($sujeito === null) {
            throw RecursoNaoEncontradoException::paraId('Sujeito', $sujeitoId);
        }

        $quantidadeDeDiscursos = 0;
        $quantidadeDeEventos = 0;

        foreach ($sujeito->sessoes() as $sessao) {
            $quantidadeDeDiscursos += count($sessao->discursos());

            foreach ($sessao->discursos() as $discurso) {
                $quantidadeDeEventos += count($discurso->eventos());
            }
        }

        return new ConsolidacaoMemoriaDTO(
            sujeitoId: $sujeitoId,
            quantidadeDeSessoes: count($sujeito->sessoes()),
            quantidadeDeDiscursos: $quantidadeDeDiscursos,
            quantidadeDeEventosDiscursivos: $quantidadeDeEventos,
            quantidadeDeMemorias: $this->quantidadeDeMemoriasDoSujeito($sujeitoId)
        );
    }

    private function quantidadeDeMemoriasDoSujeito(string $sujeitoId): int
    {
        $quantidade = 0;

        foreach ($this->memoriaRepository->findAll() as $memoria) {
            $primeiraSessao = $memoria->sessoes()[0] ?? null;

            if ($primeiraSessao === null) {
                continue;
            }

            if ($this->sessaoRepository->sujeitoIdDaSessao($primeiraSessao->id()->valor()) === $sujeitoId) {
                $quantidade++;
            }
        }

        return $quantidade;
    }
}
