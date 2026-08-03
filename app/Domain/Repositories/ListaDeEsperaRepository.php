<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Repositories;

use PsycheAI\Domain\Contracts\RepositoryInterface;
use PsycheAI\Domain\Entities\InscricaoListaDeEspera;

interface ListaDeEsperaRepository extends RepositoryInterface
{
    public function findByEmail(string $email): ?InscricaoListaDeEspera;

    /**
     * @return InscricaoListaDeEspera[]
     */
    public function findAll(): array;

    public function save(InscricaoListaDeEspera $inscricao): void;
}
