<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\ConstruirMemoriaLongitudinal;

use InvalidArgumentException;
use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;
use PsycheAI\Domain\Services\OrganizadorMemoria;
use PsycheAI\Domain\ValueObjects\Identificador;

final class ConstruirMemoriaLongitudinalHandler implements UseCaseInterface
{
    public function __construct(
        private readonly OrganizadorMemoria $organizadorMemoria = new OrganizadorMemoria()
    ) {
    }

    public function handle(ConstruirMemoriaLongitudinalCommand $command): ConstruirMemoriaLongitudinalResult
    {
        try {
            $memoria = new MemoriaLongitudinal(new Identificador($command->memoriaId));
        } catch (InvalidArgumentException $erro) {
            throw ComandoInvalidoException::fromInvalidArgument($erro);
        }

        $sessoesOrdenadas = $this->organizadorMemoria->organizar($command->sujeito->sessoes());

        foreach ($sessoesOrdenadas as $sessao) {
            $memoria->adicionarSessao($sessao);
        }

        return new ConstruirMemoriaLongitudinalResult($memoria);
    }
}
