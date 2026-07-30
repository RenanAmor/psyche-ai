<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

final class MensagemEnviadaDTO
{
    public function __construct(
        public readonly EventoDiscursivoDTO $mensagemUsuario,
        public readonly EventoDiscursivoDTO $respostaSistema
    ) {
    }
}
