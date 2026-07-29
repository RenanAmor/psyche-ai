<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarDiscurso;

use InvalidArgumentException;
use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;

final class RegistrarDiscursoHandler implements UseCaseInterface
{
    public function handle(RegistrarDiscursoCommand $command): RegistrarDiscursoResult
    {
        try {
            $discurso = new Discurso(
                new Identificador($command->id),
                new ConteudoDiscursivo($command->conteudo)
            );
        } catch (InvalidArgumentException $erro) {
            throw ComandoInvalidoException::fromInvalidArgument($erro);
        }

        $command->sessao->adicionarDiscurso($discurso);

        return new RegistrarDiscursoResult($discurso);
    }
}
