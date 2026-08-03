<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\CadastrarParticipante;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Application\DTOs\ParticipanteDTO;
use PsycheAI\Domain\Entities\Participante;

final class CadastrarParticipanteResult implements ResultInterface
{
    public function __construct(
        private readonly Participante $participante
    ) {
    }

    public function participante(): Participante
    {
        return $this->participante;
    }

    public function dto(): ParticipanteDTO
    {
        return ParticipanteDTO::fromEntity($this->participante);
    }
}
