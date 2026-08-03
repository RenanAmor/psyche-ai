<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\SujeitoDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\UseCases\CadastrarSujeito\CadastrarSujeitoCommand;
use PsycheAI\Application\UseCases\CadastrarSujeito\CadastrarSujeitoHandler;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\Repositories\SujeitoRepository;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\NomeSujeito;

/**
 * Orquestra o ciclo de vida completo de Sujeito sobre o contrato de
 * Domínio SujeitoRepository, sem conhecer a infraestrutura concreta
 * (SQLite ou qualquer outra) que o implementa.
 */
final class SujeitoApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly SujeitoRepository $sujeitoRepository,
        private readonly CadastrarSujeitoHandler $cadastrarSujeito = new CadastrarSujeitoHandler()
    ) {
    }

    public function criar(string $id, string $nome): SujeitoDTO
    {
        $sujeito = $this->cadastrarSujeito
            ->handle(new CadastrarSujeitoCommand($id, $nome))
            ->sujeito();

        $this->sujeitoRepository->save($sujeito);

        return SujeitoDTO::fromEntity($sujeito);
    }

    public function atualizar(string $id, string $novoNome): SujeitoDTO
    {
        $existente = $this->buscarEntidadePorId($id);

        $atualizado = new Sujeito(new Identificador($id), new NomeSujeito($novoNome));

        foreach ($existente->sessoes() as $sessao) {
            $atualizado->adicionarSessao($sessao);
        }

        $this->sujeitoRepository->save($atualizado);

        return SujeitoDTO::fromEntity($atualizado);
    }

    public function excluir(string $id): void
    {
        $this->sujeitoRepository->remove($this->buscarEntidadePorId($id));
    }

    public function buscarPorId(string $id): ?SujeitoDTO
    {
        $sujeito = $this->sujeitoRepository->findById($id);

        return $sujeito === null ? null : SujeitoDTO::fromEntity($sujeito);
    }

    /**
     * @return SujeitoDTO[]
     */
    public function listar(): array
    {
        return array_map(
            static fn (Sujeito $sujeito): SujeitoDTO => SujeitoDTO::fromEntity($sujeito),
            $this->sujeitoRepository->findAll()
        );
    }

    private function buscarEntidadePorId(string $id): Sujeito
    {
        $sujeito = $this->sujeitoRepository->findById($id);

        if ($sujeito === null) {
            throw RecursoNaoEncontradoException::paraId('Sujeito', $id);
        }

        return $sujeito;
    }
}
