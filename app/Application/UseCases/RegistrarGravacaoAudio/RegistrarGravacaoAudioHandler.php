<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarGravacaoAudio;

use InvalidArgumentException;
use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Domain\Entities\GravacaoAudio;
use PsycheAI\Domain\ValueObjects\Identificador;

final class RegistrarGravacaoAudioHandler implements UseCaseInterface
{
    public function handle(RegistrarGravacaoAudioCommand $command): RegistrarGravacaoAudioResult
    {
        try {
            $gravacao = new GravacaoAudio(
                new Identificador($command->id),
                $command->sessaoId,
                $command->caminhoArmazenamento
            );
        } catch (InvalidArgumentException $erro) {
            throw ComandoInvalidoException::fromInvalidArgument($erro);
        }

        return new RegistrarGravacaoAudioResult($gravacao);
    }
}
