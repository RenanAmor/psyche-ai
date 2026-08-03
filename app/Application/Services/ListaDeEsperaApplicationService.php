<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\InscricaoListaDeEsperaDTO;
use PsycheAI\Application\UseCases\InscreverNaListaDeEspera\InscreverNaListaDeEsperaCommand;
use PsycheAI\Application\UseCases\InscreverNaListaDeEspera\InscreverNaListaDeEsperaHandler;
use PsycheAI\Domain\Repositories\ListaDeEsperaRepository;
use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;

/**
 * Captura de interesse na ECO para quem esbarra no login do Participante
 * sem conta — totalmente independente de ParticipanteApplicationService/
 * AnalistaApplicationService: sem senha, sem autenticação, sem sessão.
 */
final class ListaDeEsperaApplicationService implements ApplicationServiceInterface
{
    public function __construct(
        private readonly ListaDeEsperaRepository $listaDeEsperaRepository,
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly InscreverNaListaDeEsperaHandler $inscreverNaListaDeEspera = new InscreverNaListaDeEsperaHandler()
    ) {
    }

    /**
     * Idempotente: reenviar o mesmo e-mail devolve a inscrição já
     * existente em vez de duplicar ou falhar por violar a UNIQUE de
     * `lista_espera.email` — evita expor, por erro, que alguém já está
     * na lista.
     */
    public function inscrever(
        string $email,
        string $nome,
        ?string $profissao,
        ?string $instituicao,
        string $paisEstado,
        string $motivoInteresse,
        bool $aceitouPoliticaPrivacidade,
        bool $aceitouTermoConsentimento
    ): InscricaoListaDeEsperaDTO {
        $existente = $this->listaDeEsperaRepository->findByEmail($email);

        if ($existente !== null) {
            return InscricaoListaDeEsperaDTO::fromEntity($existente);
        }

        $inscricao = $this->inscreverNaListaDeEspera
            ->handle(new InscreverNaListaDeEsperaCommand(
                $this->uuidGenerator->generate(),
                $email,
                $nome,
                $profissao,
                $instituicao,
                $paisEstado,
                $motivoInteresse,
                $aceitouPoliticaPrivacidade,
                $aceitouTermoConsentimento
            ))
            ->inscricao();

        $this->listaDeEsperaRepository->save($inscricao);

        return InscricaoListaDeEsperaDTO::fromEntity($inscricao);
    }

    /**
     * @return InscricaoListaDeEsperaDTO[]
     */
    public function listar(): array
    {
        return array_map(
            static fn ($inscricao): InscricaoListaDeEsperaDTO => InscricaoListaDeEsperaDTO::fromEntity($inscricao),
            $this->listaDeEsperaRepository->findAll()
        );
    }
}
