<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\InscreverNaListaDeEspera;

use DateTimeImmutable;
use InvalidArgumentException;
use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Domain\Entities\InscricaoListaDeEspera;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;

final class InscreverNaListaDeEsperaHandler implements UseCaseInterface
{
    public function handle(InscreverNaListaDeEsperaCommand $command): InscreverNaListaDeEsperaResult
    {
        try {
            if (trim($command->nome) === '') {
                throw new InvalidArgumentException('O nome não pode ser vazio.');
            }

            if (trim($command->paisEstado) === '') {
                throw new InvalidArgumentException('País/Estado não pode ser vazio.');
            }

            if (trim($command->motivoInteresse) === '') {
                throw new InvalidArgumentException('O motivo do interesse não pode ser vazio.');
            }

            if (!$command->aceitouPoliticaPrivacidade) {
                throw new InvalidArgumentException('É necessário aceitar a Política de Privacidade.');
            }

            if (!$command->aceitouTermoConsentimento) {
                throw new InvalidArgumentException('É necessário aceitar o Termo de Consentimento.');
            }

            $inscricao = new InscricaoListaDeEspera(
                new Identificador($command->id),
                new Email($command->email),
                $command->nome,
                $command->profissao,
                $command->instituicao,
                $command->paisEstado,
                $command->motivoInteresse,
                $command->aceitouPoliticaPrivacidade,
                $command->aceitouTermoConsentimento,
                new DateTimeImmutable()
            );
        } catch (InvalidArgumentException $erro) {
            throw ComandoInvalidoException::fromInvalidArgument($erro);
        }

        return new InscreverNaListaDeEsperaResult($inscricao);
    }
}
