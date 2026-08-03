<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Repositories;

use PsycheAI\Domain\Contracts\RepositoryInterface;
use PsycheAI\Domain\Entities\Participante;

interface ParticipanteRepository extends RepositoryInterface
{
    public function findById(string $id): ?Participante;

    public function findByEmail(string $email): ?Participante;

    public function save(Participante $participante): void;
}
