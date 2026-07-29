<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarEventoDiscursivo;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Application\DTOs\EventoDiscursivoDTO;
use PsycheAI\Domain\Entities\EventoDiscursivo;

final class RegistrarEventoDiscursivoResult implements ResultInterface
{
    public function __construct(
        private readonly EventoDiscursivo $evento
    ) {
    }

    public function evento(): EventoDiscursivo
    {
        return $this->evento;
    }

    public function dto(): EventoDiscursivoDTO
    {
        return EventoDiscursivoDTO::fromEntity($this->evento);
    }
}
