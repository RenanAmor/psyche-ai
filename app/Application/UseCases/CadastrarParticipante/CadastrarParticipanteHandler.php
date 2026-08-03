<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\CadastrarParticipante;

use DateTimeImmutable;
use InvalidArgumentException;
use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Domain\Entities\Participante;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;

final class CadastrarParticipanteHandler implements UseCaseInterface
{
    public function handle(CadastrarParticipanteCommand $command): CadastrarParticipanteResult
    {
        try {
            if (trim($command->senha) === '') {
                throw new InvalidArgumentException('A senha não pode ser vazia.');
            }

            $participante = new Participante(
                new Identificador($command->id),
                new Email($command->email),
                password_hash($command->senha, PASSWORD_DEFAULT),
                new DateTimeImmutable()
            );
        } catch (InvalidArgumentException $erro) {
            throw ComandoInvalidoException::fromInvalidArgument($erro);
        }

        return new CadastrarParticipanteResult($participante);
    }
}
