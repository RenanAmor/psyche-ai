<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarEventoDiscursivo;

use PsycheAI\Application\Contracts\CommandInterface;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\ValueObjects\Locutor;

final class RegistrarEventoDiscursivoCommand implements CommandInterface
{
    public function __construct(
        public readonly Discurso $discurso,
        public readonly string $id,
        public readonly string $conteudo,
        public readonly int $posicao,
        public readonly Locutor $locutor = Locutor::Desconhecido
    ) {
    }
}
