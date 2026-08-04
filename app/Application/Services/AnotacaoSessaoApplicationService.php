<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\AnotacaoSessaoDTO;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Application\UseCases\RegistrarAnotacaoSessao\RegistrarAnotacaoSessaoCommand;
use PsycheAI\Application\UseCases\RegistrarAnotacaoSessao\RegistrarAnotacaoSessaoHandler;
use PsycheAI\Domain\Repositories\AnotacaoSessaoRepository;
use PsycheAI\Domain\Repositories\SessaoRepository;
use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;

/**
 * Orquestra as anotações livres do analista sobre uma Sessao (autosave).
 * Sempre upsert: cada chamada a salvar() gera uma nova AnotacaoSessao, e o
 * mapper resolve o conflito por sessao_id (UNIQUE) — não há necessidade de
 * buscar-a-existente-para-atualizar, o que mantém o caminho de escrita
 * (chamado a cada poucos segundos pelo autosave) o mais simples possível.
 */
final class AnotacaoSessaoApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly AnotacaoSessaoRepository $anotacaoRepository,
        private readonly SessaoRepository $sessaoRepository,
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly RegistrarAnotacaoSessaoHandler $registrarAnotacao = new RegistrarAnotacaoSessaoHandler()
    ) {
    }

    public function salvar(string $sessaoId, string $conteudo): AnotacaoSessaoDTO
    {
        if ($this->sessaoRepository->findById($sessaoId) === null) {
            throw RecursoNaoEncontradoException::paraId('Sessao', $sessaoId);
        }

        $anotacao = $this->registrarAnotacao
            ->handle(new RegistrarAnotacaoSessaoCommand($this->uuidGenerator->generate(), $sessaoId, $conteudo))
            ->anotacao();

        $this->anotacaoRepository->save($anotacao);

        return AnotacaoSessaoDTO::fromEntity($anotacao);
    }

    public function buscarPorSessao(string $sessaoId): AnotacaoSessaoDTO
    {
        $anotacao = $this->anotacaoRepository->findBySessaoId($sessaoId);

        return $anotacao === null ? AnotacaoSessaoDTO::vazia($sessaoId) : AnotacaoSessaoDTO::fromEntity($anotacao);
    }
}
