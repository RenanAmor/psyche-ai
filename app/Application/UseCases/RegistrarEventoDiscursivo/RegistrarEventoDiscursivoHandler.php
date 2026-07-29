<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarEventoDiscursivo;

use InvalidArgumentException;
use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;

final class RegistrarEventoDiscursivoHandler implements UseCaseInterface
{
    public function handle(RegistrarEventoDiscursivoCommand $command): RegistrarEventoDiscursivoResult
    {
        try {
            $evento = new EventoDiscursivo(
                new Identificador($command->id),
                new ConteudoDiscursivo($command->conteudo),
                new Posicao($command->posicao)
            );
        } catch (InvalidArgumentException $erro) {
            throw ComandoInvalidoException::fromInvalidArgument($erro);
        }

        $command->discurso->adicionarEvento($evento);

        return new RegistrarEventoDiscursivoResult($evento);
    }
}
