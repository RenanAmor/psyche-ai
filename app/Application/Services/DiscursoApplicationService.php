<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\DiscursoDTO;
use PsycheAI\Application\DTOs\EventoDiscursivoDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\UseCases\RegistrarDiscurso\RegistrarDiscursoCommand;
use PsycheAI\Application\UseCases\RegistrarDiscurso\RegistrarDiscursoHandler;
use PsycheAI\Application\UseCases\RegistrarEventoDiscursivo\RegistrarEventoDiscursivoCommand;
use PsycheAI\Application\UseCases\RegistrarEventoDiscursivo\RegistrarEventoDiscursivoHandler;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Repositories\DiscursoRepository;
use PsycheAI\Domain\Repositories\SessaoRepository;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;

/**
 * Orquestra o ciclo de vida completo de Discurso. A criação exige o
 * SessaoRepository pelo mesmo motivo do vínculo Sujeito/Sessao: o
 * Discurso só é persistido com a chave estrangeira correta quando salvo
 * em cascata através da Sessao que o contém.
 *
 * EventoDiscursivo não possui repositório de Domínio próprio (não é raiz
 * de agregado — é persistido apenas em cascata através de Discurso), por
 * isso seu registro é exposto aqui como uma operação do agregado
 * Discurso, e não como um serviço à parte.
 */
final class DiscursoApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly DiscursoRepository $discursoRepository,
        private readonly SessaoRepository $sessaoRepository,
        private readonly RegistrarDiscursoHandler $registrarDiscurso = new RegistrarDiscursoHandler(),
        private readonly RegistrarEventoDiscursivoHandler $registrarEvento = new RegistrarEventoDiscursivoHandler()
    ) {
    }

    public function criar(string $sessaoId, string $id, string $conteudo): DiscursoDTO
    {
        $sessao = $this->sessaoRepository->findById($sessaoId);

        if ($sessao === null) {
            throw RecursoNaoEncontradoException::paraId('Sessao', $sessaoId);
        }

        $discurso = $this->registrarDiscurso
            ->handle(new RegistrarDiscursoCommand($sessao, $id, $conteudo))
            ->discurso();

        $this->sessaoRepository->save($sessao);

        return DiscursoDTO::fromEntity($discurso);
    }

    public function atualizar(string $id, string $novoConteudo): DiscursoDTO
    {
        $existente = $this->buscarEntidadePorId($id);

        $atualizado = new Discurso(new Identificador($id), new ConteudoDiscursivo($novoConteudo));

        foreach ($existente->eventos() as $evento) {
            $atualizado->adicionarEvento($evento);
        }

        $this->discursoRepository->save($atualizado);

        return DiscursoDTO::fromEntity($atualizado);
    }

    public function adicionarEvento(string $discursoId, string $eventoId, string $conteudo, int $posicao): DiscursoDTO
    {
        $discurso = $this->buscarEntidadePorId($discursoId);

        $this->registrarEvento->handle(
            new RegistrarEventoDiscursivoCommand($discurso, $eventoId, $conteudo, $posicao)
        );

        $this->discursoRepository->save($discurso);

        return DiscursoDTO::fromEntity($discurso);
    }

    /**
     * @return EventoDiscursivoDTO[]
     */
    public function listarEventos(): array
    {
        $itens = [];

        foreach ($this->discursoRepository->findAll() as $discurso) {
            $sessaoId = $this->discursoRepository->sessaoIdDoDiscurso($discurso->id()->valor());

            foreach ($discurso->eventos() as $evento) {
                $itens[] = EventoDiscursivoDTO::fromEntity($evento, $discurso->id()->valor(), $sessaoId);
            }
        }

        return $itens;
    }

    public function buscarEvento(string $eventoId): ?EventoDiscursivoDTO
    {
        foreach ($this->discursoRepository->findAll() as $discurso) {
            foreach ($discurso->eventos() as $evento) {
                if ($evento->id()->valor() === $eventoId) {
                    $sessaoId = $this->discursoRepository->sessaoIdDoDiscurso($discurso->id()->valor());

                    return EventoDiscursivoDTO::fromEntity($evento, $discurso->id()->valor(), $sessaoId);
                }
            }
        }

        return null;
    }

    public function atualizarEvento(string $eventoId, string $novoConteudo, int $novaPosicao): EventoDiscursivoDTO
    {
        $discurso = $this->buscarDiscursoDoEvento($eventoId);
        $original = $this->buscarEventoNoDiscurso($discurso, $eventoId);

        $atualizado = new Discurso($discurso->id(), $discurso->conteudo());
        $eventoAtualizado = new EventoDiscursivo(
            new Identificador($eventoId),
            new ConteudoDiscursivo($novoConteudo),
            new Posicao($novaPosicao),
            $original->criadoEm()
        );

        foreach ($discurso->eventos() as $evento) {
            $atualizado->adicionarEvento(
                $evento->id()->valor() === $eventoId ? $eventoAtualizado : $evento
            );
        }

        $this->discursoRepository->save($atualizado);

        $sessaoId = $this->discursoRepository->sessaoIdDoDiscurso($discurso->id()->valor());

        return EventoDiscursivoDTO::fromEntity($eventoAtualizado, $discurso->id()->valor(), $sessaoId);
    }

    public function removerEvento(string $eventoId): void
    {
        $discurso = $this->buscarDiscursoDoEvento($eventoId);

        $atualizado = new Discurso($discurso->id(), $discurso->conteudo());

        foreach ($discurso->eventos() as $evento) {
            if ($evento->id()->valor() !== $eventoId) {
                $atualizado->adicionarEvento($evento);
            }
        }

        $this->discursoRepository->save($atualizado);
    }

    public function excluir(string $id): void
    {
        $this->discursoRepository->remove($this->buscarEntidadePorId($id));
    }

    public function buscarPorId(string $id): ?DiscursoDTO
    {
        $discurso = $this->discursoRepository->findById($id);

        return $discurso === null ? null : DiscursoDTO::fromEntity($discurso);
    }

    /**
     * @return DiscursoDTO[]
     */
    public function listar(): array
    {
        return array_map(
            static fn (Discurso $discurso): DiscursoDTO => DiscursoDTO::fromEntity($discurso),
            $this->discursoRepository->findAll()
        );
    }

    private function buscarEntidadePorId(string $id): Discurso
    {
        $discurso = $this->discursoRepository->findById($id);

        if ($discurso === null) {
            throw RecursoNaoEncontradoException::paraId('Discurso', $id);
        }

        return $discurso;
    }

    /**
     * Localiza o Discurso que contém o EventoDiscursivo informado.
     * EventoDiscursivo não possui repositório próprio (não é raiz de
     * agregado), por isso "buscar por id" percorre os Discursos.
     */
    private function buscarDiscursoDoEvento(string $eventoId): Discurso
    {
        foreach ($this->discursoRepository->findAll() as $discurso) {
            foreach ($discurso->eventos() as $evento) {
                if ($evento->id()->valor() === $eventoId) {
                    return $discurso;
                }
            }
        }

        throw RecursoNaoEncontradoException::paraId('EventoDiscursivo', $eventoId);
    }

    private function buscarEventoNoDiscurso(Discurso $discurso, string $eventoId): EventoDiscursivo
    {
        foreach ($discurso->eventos() as $evento) {
            if ($evento->id()->valor() === $eventoId) {
                return $evento;
            }
        }

        throw RecursoNaoEncontradoException::paraId('EventoDiscursivo', $eventoId);
    }
}
