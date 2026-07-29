<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\CadastrarSujeito;

use InvalidArgumentException;
use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\NomeSujeito;

final class CadastrarSujeitoHandler implements UseCaseInterface
{
    public function handle(CadastrarSujeitoCommand $command): CadastrarSujeitoResult
    {
        try {
            $sujeito = new Sujeito(
                new Identificador($command->id),
                new NomeSujeito($command->nome)
            );
        } catch (InvalidArgumentException $erro) {
            throw ComandoInvalidoException::fromInvalidArgument($erro);
        }

        return new CadastrarSujeitoResult($sujeito);
    }
}
