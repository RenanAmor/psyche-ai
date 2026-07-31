<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\AnalistaDTO;
use PsycheAI\Application\UseCases\CadastrarAnalista\CadastrarAnalistaCommand;
use PsycheAI\Application\UseCases\CadastrarAnalista\CadastrarAnalistaHandler;
use PsycheAI\Domain\Repositories\AnalistaRepository;
use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;

/**
 * Sprint 18 (Plataforma): substitui a senha única compartilhada de
 * `PortaoDeAnalista` (revisão pós-Sprint 16) por contas reais. Continua
 * um único papel ("analista") nesta passagem — sem permissões ou
 * administração granulares (ver docs/Roadmap.md).
 */
final class AnalistaApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly AnalistaRepository $analistaRepository,
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly CadastrarAnalistaHandler $cadastrarAnalista = new CadastrarAnalistaHandler()
    ) {
    }

    public function criar(string $email, string $senha): AnalistaDTO
    {
        $analista = $this->cadastrarAnalista
            ->handle(new CadastrarAnalistaCommand($this->uuidGenerator->generate(), $email, $senha))
            ->analista();

        $this->analistaRepository->save($analista);

        return AnalistaDTO::fromEntity($analista);
    }

    public function buscarPorEmail(string $email): ?AnalistaDTO
    {
        $analista = $this->analistaRepository->findByEmail($email);

        return $analista === null ? null : AnalistaDTO::fromEntity($analista);
    }

    /**
     * Retorna null tanto para e-mail inexistente quanto para senha
     * incorreta — a distinção não deve vazar para quem chama (evita dar
     * pista de quais e-mails têm conta).
     */
    public function autenticar(string $email, string $senha): ?AnalistaDTO
    {
        $analista = $this->analistaRepository->findByEmail($email);

        if ($analista === null || !$analista->verificarSenha($senha)) {
            return null;
        }

        return AnalistaDTO::fromEntity($analista);
    }
}
