<?php

declare(strict_types=1);

namespace PsycheAI\Application\Services;

use PsycheAI\Application\Contracts\ApplicationServiceInterface;
use PsycheAI\Application\DTOs\InscricaoListaDeEsperaDTO;
use PsycheAI\Application\UseCases\InscreverNaListaDeEspera\InscreverNaListaDeEsperaCommand;
use PsycheAI\Application\UseCases\InscreverNaListaDeEspera\InscreverNaListaDeEsperaHandler;
use PsycheAI\Domain\Entities\InscricaoListaDeEspera;
use PsycheAI\Domain\Repositories\ListaDeEsperaRepository;
use PsycheAI\Infrastructure\Contracts\EmailInterface;
use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;
use Throwable;

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
        private readonly EmailInterface $email,
        private readonly InscreverNaListaDeEsperaHandler $inscreverNaListaDeEspera = new InscreverNaListaDeEsperaHandler()
    ) {
    }

    /**
     * Idempotente: reenviar o mesmo e-mail devolve a inscrição já
     * existente em vez de duplicar ou falhar por violar a UNIQUE de
     * `lista_espera.email` — evita expor, por erro, que alguém já está
     * na lista. O e-mail de confirmação só é disparado na primeira
     * inscrição (nunca num reenvio idempotente).
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
        $this->enviarEmailDeConfirmacao($inscricao);

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

    /**
     * Falha de envio nunca deve derrubar a inscrição, que já foi
     * persistida com sucesso — só registrada, nunca propagada.
     */
    private function enviarEmailDeConfirmacao(InscricaoListaDeEspera $inscricao): void
    {
        try {
            $this->email->enviar(
                $inscricao->email()->valor(),
                $inscricao->nome(),
                'Você entrou na lista de espera da ECO',
                sprintf(
                    "Olá, %s!\n\n" .
                    "Recebemos sua inscrição na lista de espera da ECO (PsycheAI). " .
                    "Assim que houver vaga disponível, entraremos em contato por este e-mail.\n\n" .
                    "Qualquer dúvida, escreva para contato@investimentos369.com.\n\n" .
                    "— Equipe PsycheAI",
                    $inscricao->nome()
                )
            );
        } catch (Throwable $erro) {
            error_log(sprintf('Falha ao enviar e-mail de confirmação da lista de espera: %s', $erro->getMessage()));
        }
    }
}
