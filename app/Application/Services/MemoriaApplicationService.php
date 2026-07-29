<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\MemoriaLongitudinalDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal\ConstruirMemoriaLongitudinalCommand;
use PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal\ConstruirMemoriaLongitudinalHandler;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\Repositories\MemoriaRepository;

/**
 * Orquestra o ciclo de vida completo de MemoriaLongitudinal sobre o
 * contrato de Domínio MemoriaRepository. Tanto "criar" quanto
 * "atualizar" reconstroem a memória a partir das sessões atuais do
 * Sujeito informado; o repositório SQLite trata a gravação como upsert,
 * substituindo o pivô memoria_sessoes por completo a cada save.
 */
final class MemoriaApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly MemoriaRepository $memoriaRepository,
        private readonly ConstruirMemoriaLongitudinalHandler $construirMemoria = new ConstruirMemoriaLongitudinalHandler()
    ) {
    }

    public function criar(Sujeito $sujeito, string $memoriaId): MemoriaLongitudinalDTO
    {
        $memoria = $this->construirMemoria
            ->handle(new ConstruirMemoriaLongitudinalCommand($sujeito, $memoriaId))
            ->memoria();

        $this->memoriaRepository->save($memoria);

        return MemoriaLongitudinalDTO::fromEntity($memoria);
    }

    public function atualizar(Sujeito $sujeito, string $memoriaId): MemoriaLongitudinalDTO
    {
        $this->buscarEntidadePorId($memoriaId);

        return $this->criar($sujeito, $memoriaId);
    }

    public function excluir(string $id): void
    {
        $this->memoriaRepository->remove($this->buscarEntidadePorId($id));
    }

    public function buscarPorId(string $id): ?MemoriaLongitudinalDTO
    {
        $memoria = $this->memoriaRepository->findById($id);

        return $memoria === null ? null : MemoriaLongitudinalDTO::fromEntity($memoria);
    }

    /**
     * @return MemoriaLongitudinalDTO[]
     */
    public function listar(): array
    {
        return array_map(
            static fn (MemoriaLongitudinal $memoria): MemoriaLongitudinalDTO => MemoriaLongitudinalDTO::fromEntity($memoria),
            $this->memoriaRepository->findAll()
        );
    }

    private function buscarEntidadePorId(string $id): MemoriaLongitudinal
    {
        $memoria = $this->memoriaRepository->findById($id);

        if ($memoria === null) {
            throw RecursoNaoEncontradoException::paraId('MemoriaLongitudinal', $id);
        }

        return $memoria;
    }
}
