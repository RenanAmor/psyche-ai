<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarSessao;

use InvalidArgumentException;
use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;

final class RegistrarSessaoHandler implements UseCaseInterface
{
    public function handle(RegistrarSessaoCommand $command): RegistrarSessaoResult
    {
        try {
            $sessao = new Sessao(
                new Identificador($command->id),
                new DataSessao($command->data)
            );
        } catch (InvalidArgumentException $erro) {
            throw ComandoInvalidoException::fromInvalidArgument($erro);
        }

        $command->sujeito->adicionarSessao($sessao);

        return new RegistrarSessaoResult($sessao);
    }
}
