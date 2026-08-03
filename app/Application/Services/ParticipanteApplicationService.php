<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\ParticipanteDTO;
use PsycheAI\Application\UseCases\CadastrarParticipante\CadastrarParticipanteCommand;
use PsycheAI\Application\UseCases\CadastrarParticipante\CadastrarParticipanteHandler;
use PsycheAI\Domain\Repositories\ParticipanteRepository;
use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;

/**
 * Contas de acesso à ECO, provisionadas pelo Analista (sem autocadastro
 * público — ao contrário da extinta Sprint 20). Sistema de autenticação
 * totalmente independente de AnalistaApplicationService: tabela, sessão e
 * permissões próprias.
 */
final class ParticipanteApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly ParticipanteRepository $participanteRepository,
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly CadastrarParticipanteHandler $cadastrarParticipante = new CadastrarParticipanteHandler()
    ) {
    }

    public function criar(string $email, string $senha): ParticipanteDTO
    {
        $participante = $this->cadastrarParticipante
            ->handle(new CadastrarParticipanteCommand($this->uuidGenerator->generate(), $email, $senha))
            ->participante();

        $this->participanteRepository->save($participante);

        return ParticipanteDTO::fromEntity($participante);
    }

    public function buscarPorEmail(string $email): ?ParticipanteDTO
    {
        $participante = $this->participanteRepository->findByEmail($email);

        return $participante === null ? null : ParticipanteDTO::fromEntity($participante);
    }

    /**
     * Retorna null tanto para e-mail inexistente quanto para senha
     * incorreta — a distinção não deve vazar para quem chama (evita dar
     * pista de quais e-mails têm conta).
     */
    public function autenticar(string $email, string $senha): ?ParticipanteDTO
    {
        $participante = $this->participanteRepository->findByEmail($email);

        if ($participante === null || !$participante->verificarSenha($senha)) {
            return null;
        }

        return ParticipanteDTO::fromEntity($participante);
    }
}
