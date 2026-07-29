<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Repositories;

use PsycheAI\Domain\Contracts\RepositoryInterface;
use PsycheAI\Domain\Entities\Discurso;

interface DiscursoRepository extends RepositoryInterface
{
    public function findById(string $id): ?Discurso;

    /**
     * @return Discurso[]
     */
    public function findAll(): array;

    public function save(Discurso $discurso): void;

    public function remove(Discurso $discurso): void;

    /**
     * Expõe o vínculo persistido Discurso -> Sessao, que a Entidade
     * Discurso não carrega (não conhece o agregado que a contém). Usado
     * para enriquecer leituras (ex.: DTO de EventoDiscursivo) sem exigir
     * um repositório dedicado só para essa consulta.
     */
    public function sessaoIdDoDiscurso(string $discursoId): ?string;
}