<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarAnotacaoSessao;

use DateTimeImmutable;
use InvalidArgumentException;
use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Domain\Entities\AnotacaoSessao;
use PsycheAI\Domain\ValueObjects\ConteudoAnotacao;
use PsycheAI\Domain\ValueObjects\Identificador;

final class RegistrarAnotacaoSessaoHandler implements UseCaseInterface
{
    public function handle(RegistrarAnotacaoSessaoCommand $command): RegistrarAnotacaoSessaoResult
    {
        try {
            $anotacao = new AnotacaoSessao(
                new Identificador($command->id),
                $command->sessaoId,
                new ConteudoAnotacao($command->conteudo),
                new DateTimeImmutable()
            );
        } catch (InvalidArgumentException $erro) {
            throw ComandoInvalidoException::fromInvalidArgument($erro);
        }

        return new RegistrarAnotacaoSessaoResult($anotacao);
    }
}
