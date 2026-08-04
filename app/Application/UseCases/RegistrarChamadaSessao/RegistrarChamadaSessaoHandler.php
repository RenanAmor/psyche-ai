<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarChamadaSessao;

use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Domain\Entities\ChamadaSessao;
use PsycheAI\Domain\ValueObjects\Identificador;

final class RegistrarChamadaSessaoHandler implements UseCaseInterface
{
    public function handle(RegistrarChamadaSessaoCommand $command): RegistrarChamadaSessaoResult
    {
        $chamada = new ChamadaSessao(
            new Identificador($command->id),
            $command->sessaoId,
            $command->salaProvedorId,
            $command->salaUrl,
            $command->tokenAcesso,
            $command->criadaEm,
            $command->expiraEm
        );

        return new RegistrarChamadaSessaoResult($chamada);
    }
}
