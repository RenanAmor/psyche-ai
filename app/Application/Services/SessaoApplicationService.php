<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use DateTimeImmutable;
use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\SessaoDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\UseCases\RegistrarSessao\RegistrarSessaoCommand;
use PsycheAI\Application\UseCases\RegistrarSessao\RegistrarSessaoHandler;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Repositories\SessaoRepository;
use PsycheAI\Domain\Repositories\SujeitoRepository;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;

/**
 * Orquestra o ciclo de vida completo de Sessao. A criação exige o
 * SujeitoRepository porque uma Sessao nova só é persistida com o vínculo
 * correto ao Sujeito (chave estrangeira) quando salva em cascata através
 * do agregado raiz Sujeito; leitura, atualização e remoção operam
 * diretamente pelo SessaoRepository.
 */
final class SessaoApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly SessaoRepository $sessaoRepository,
        private readonly SujeitoRepository $sujeitoRepository,
        private readonly RegistrarSessaoHandler $registrarSessao = new RegistrarSessaoHandler()
    ) {
    }

    public function criar(string $sujeitoId, string $id, DateTimeImmutable $data): SessaoDTO
    {
        $sujeito = $this->sujeitoRepository->findById($sujeitoId);

        if ($sujeito === null) {
            throw RecursoNaoEncontradoException::paraId('Sujeito', $sujeitoId);
        }

        $sessao = $this->registrarSessao
            ->handle(new RegistrarSessaoCommand($sujeito, $id, $data))
            ->sessao();

        $this->sujeitoRepository->save($sujeito);

        return SessaoDTO::fromEntity($sessao);
    }

    public function atualizar(string $id, DateTimeImmutable $novaData): SessaoDTO
    {
        $existente = $this->buscarEntidadePorId($id);

        $atualizada = new Sessao(new Identificador($id), new DataSessao($novaData));

        foreach ($existente->discursos() as $discurso) {
            $atualizada->adicionarDiscurso($discurso);
        }

        $this->sessaoRepository->save($atualizada);

        return SessaoDTO::fromEntity($atualizada);
    }

    public function excluir(string $id): void
    {
        $this->sessaoRepository->remove($this->buscarEntidadePorId($id));
    }

    public function buscarPorId(string $id): ?SessaoDTO
    {
        $sessao = $this->sessaoRepository->findById($id);

        return $sessao === null ? null : SessaoDTO::fromEntity($sessao);
    }

    /**
     * @return SessaoDTO[]
     */
    public function listar(): array
    {
        return array_map(
            static fn (Sessao $sessao): SessaoDTO => SessaoDTO::fromEntity($sessao),
            $this->sessaoRepository->findAll()
        );
    }

    private function buscarEntidadePorId(string $id): Sessao
    {
        $sessao = $this->sessaoRepository->findById($id);

        if ($sessao === null) {
            throw RecursoNaoEncontradoException::paraId('Sessao', $id);
        }

        return $sessao;
    }
}
