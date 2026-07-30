<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use DateTimeImmutable;
use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\LinhaDoTempoItemDTO;
use PsycheAI\Application\DTOs\LinhaDoTempoResultadoDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\Repositories\MemoriaRepository;
use PsycheAI\Domain\Repositories\SessaoRepository;
use PsycheAI\Domain\Repositories\SujeitoRepository;

/**
 * Consulta somente-leitura da Linha do Tempo Discursiva de um Sujeito:
 * Sessões, Discursos, Eventos Discursivos e Memórias Longitudinais já
 * registrados, projetados em um formato comum e ordenados
 * cronologicamente — sem interpretar, agrupar por recorrência ou
 * produzir qualquer leitura clínica sobre o conteúdo.
 *
 * Discurso e MemoriaLongitudinal não possuem timestamp próprio no
 * Domínio (apenas EventoDiscursivo::criadoEm() e Sessao::data() têm).
 * Por isso cada Discurso é ancorado na data da Sessao que o contém, e
 * cada MemoriaLongitudinal na data da última Sessao que consolida —
 * uma decisão estrutural, não uma inferência sobre o conteúdo.
 */
final class LinhaDoTempoApplicationService implements ApplicationServiceInterface
{
    private const ORDEM_TIPO = [
        LinhaDoTempoItemDTO::TIPO_SESSAO => 0,
        LinhaDoTempoItemDTO::TIPO_DISCURSO => 1,
        LinhaDoTempoItemDTO::TIPO_EVENTO => 2,
        LinhaDoTempoItemDTO::TIPO_MEMORIA => 3,
    ];

    public function __construct(
        private readonly SujeitoRepository $sujeitoRepository,
        private readonly MemoriaRepository $memoriaRepository,
        private readonly SessaoRepository $sessaoRepository
    ) {
    }

    public function consultar(
        string $sujeitoId,
        ?string $tipo,
        ?DateTimeImmutable $de,
        ?DateTimeImmutable $ate,
        ?string $q,
        int $pagina,
        int $porPagina
    ): LinhaDoTempoResultadoDTO {
        $sujeito = $this->sujeitoRepository->findById($sujeitoId);

        if ($sujeito === null) {
            throw RecursoNaoEncontradoException::paraId('Sujeito', $sujeitoId);
        }

        $itens = $this->ordenar($this->filtrar($this->montarItens($sujeito), $tipo, $de, $ate, $q));

        $total = count($itens);
        $totalDePaginas = $total === 0 ? 0 : (int) ceil($total / $porPagina);
        $itensDaPagina = array_slice($itens, ($pagina - 1) * $porPagina, $porPagina);

        return new LinhaDoTempoResultadoDTO($itensDaPagina, $pagina, $porPagina, $total, $totalDePaginas);
    }

    /**
     * @return LinhaDoTempoItemDTO[]
     */
    private function montarItens(Sujeito $sujeito): array
    {
        $itens = [];

        foreach ($sujeito->sessoes() as $sessao) {
            $itens[] = $this->itemDeSessao($sessao);

            foreach ($sessao->discursos() as $discurso) {
                $itens[] = $this->itemDeDiscurso($discurso, $sessao);

                foreach ($discurso->eventos() as $evento) {
                    $itens[] = $this->itemDeEvento($evento, $discurso, $sessao);
                }
            }
        }

        foreach ($this->memoriasDoSujeito($sujeito->id()->valor()) as $memoria) {
            $itens[] = $this->itemDeMemoria($memoria);
        }

        return $itens;
    }

    private function itemDeSessao(Sessao $sessao): LinhaDoTempoItemDTO
    {
        return new LinhaDoTempoItemDTO(
            tipo: LinhaDoTempoItemDTO::TIPO_SESSAO,
            id: $sessao->id()->valor(),
            timestamp: $sessao->data()->valor()->format('Y-m-d H:i:s'),
            dados: [
                'id' => $sessao->id()->valor(),
                'data' => (string) $sessao->data(),
                'quantidadeDeDiscursos' => count($sessao->discursos()),
            ]
        );
    }

    private function itemDeDiscurso(Discurso $discurso, Sessao $sessao): LinhaDoTempoItemDTO
    {
        return new LinhaDoTempoItemDTO(
            tipo: LinhaDoTempoItemDTO::TIPO_DISCURSO,
            id: $discurso->id()->valor(),
            timestamp: $sessao->data()->valor()->format('Y-m-d H:i:s'),
            dados: [
                'id' => $discurso->id()->valor(),
                'sessaoId' => $sessao->id()->valor(),
                'conteudo' => $discurso->conteudo()->valor(),
                'quantidadeDeEventos' => count($discurso->eventos()),
            ]
        );
    }

    private function itemDeEvento(EventoDiscursivo $evento, Discurso $discurso, Sessao $sessao): LinhaDoTempoItemDTO
    {
        return new LinhaDoTempoItemDTO(
            tipo: LinhaDoTempoItemDTO::TIPO_EVENTO,
            id: $evento->id()->valor(),
            timestamp: $evento->criadoEm()->format('Y-m-d H:i:s'),
            dados: [
                'id' => $evento->id()->valor(),
                'discursoId' => $discurso->id()->valor(),
                'sessaoId' => $sessao->id()->valor(),
                'conteudo' => $evento->conteudo()->valor(),
                'posicao' => $evento->posicao()->valor(),
                'criadoEm' => $evento->criadoEm()->format('Y-m-d H:i:s'),
            ]
        );
    }

    private function itemDeMemoria(MemoriaLongitudinal $memoria): LinhaDoTempoItemDTO
    {
        $sessoes = $memoria->sessoes();
        $ultimaSessao = $sessoes === [] ? null : $sessoes[array_key_last($sessoes)];

        return new LinhaDoTempoItemDTO(
            tipo: LinhaDoTempoItemDTO::TIPO_MEMORIA,
            id: $memoria->id()->valor(),
            timestamp: $ultimaSessao?->data()->valor()->format('Y-m-d H:i:s') ?? '',
            dados: [
                'id' => $memoria->id()->valor(),
                'quantidadeDeSessoes' => $memoria->quantidadeDeSessoes(),
            ]
        );
    }

    /**
     * Localiza as MemoriaLongitudinal cujas sessões pertencem ao Sujeito
     * informado. MemoriaLongitudinal não guarda o id do Sujeito
     * diretamente (apenas as sessões que consolida) — o vínculo é
     * resolvido a partir de uma de suas sessões via
     * SessaoRepository::sujeitoIdDaSessao().
     *
     * @return MemoriaLongitudinal[]
     */
    private function memoriasDoSujeito(string $sujeitoId): array
    {
        $memorias = [];

        foreach ($this->memoriaRepository->findAll() as $memoria) {
            $primeiraSessao = $memoria->sessoes()[0] ?? null;

            if ($primeiraSessao === null) {
                continue;
            }

            if ($this->sessaoRepository->sujeitoIdDaSessao($primeiraSessao->id()->valor()) === $sujeitoId) {
                $memorias[] = $memoria;
            }
        }

        return $memorias;
    }

    /**
     * @param LinhaDoTempoItemDTO[] $itens
     * @return LinhaDoTempoItemDTO[]
     */
    private function filtrar(array $itens, ?string $tipo, ?DateTimeImmutable $de, ?DateTimeImmutable $ate, ?string $q): array
    {
        return array_values(array_filter(
            $itens,
            function (LinhaDoTempoItemDTO $item) use ($tipo, $de, $ate, $q): bool {
                if ($tipo !== null && $item->tipo !== $tipo) {
                    return false;
                }

                if ($item->timestamp !== '' && !$this->dentroDoIntervalo($item->timestamp, $de, $ate)) {
                    return false;
                }

                if ($q !== null && $q !== '' && !$this->contemTexto($item, $q)) {
                    return false;
                }

                return true;
            }
        ));
    }

    private function dentroDoIntervalo(string $timestamp, ?DateTimeImmutable $de, ?DateTimeImmutable $ate): bool
    {
        $momento = new DateTimeImmutable($timestamp);

        if ($de !== null && $momento < $de) {
            return false;
        }

        if ($ate !== null && $momento > $ate) {
            return false;
        }

        return true;
    }

    private function contemTexto(LinhaDoTempoItemDTO $item, string $q): bool
    {
        $agulha = mb_strtolower($q);

        foreach ($item->dados as $valor) {
            if (is_string($valor) && str_contains(mb_strtolower($valor), $agulha)) {
                return true;
            }
        }

        return str_contains(mb_strtolower($item->id), $agulha);
    }

    /**
     * @param LinhaDoTempoItemDTO[] $itens
     * @return LinhaDoTempoItemDTO[]
     */
    private function ordenar(array $itens): array
    {
        usort(
            $itens,
            static function (LinhaDoTempoItemDTO $a, LinhaDoTempoItemDTO $b): int {
                $comparacaoTimestamp = $a->timestamp <=> $b->timestamp;

                if ($comparacaoTimestamp !== 0) {
                    return $comparacaoTimestamp;
                }

                $comparacaoTipo = (self::ORDEM_TIPO[$a->tipo] ?? 99) <=> (self::ORDEM_TIPO[$b->tipo] ?? 99);

                if ($comparacaoTipo !== 0) {
                    return $comparacaoTipo;
                }

                return $a->id <=> $b->id;
            }
        );

        return $itens;
    }
}
