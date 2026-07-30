<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Repositories;

use PsycheAI\Domain\Contracts\RepositoryInterface;
use PsycheAI\Domain\Entities\Sessao;

interface SessaoRepository extends RepositoryInterface
{
    public function findById(string $id): ?Sessao;

    /**
     * @return Sessao[]
     */
    public function findAll(): array;

    public function save(Sessao $sessao): void;

    public function remove(Sessao $sessao): void;

    /**
     * Expõe o vínculo persistido Sessao -> Sujeito, que a Entidade Sessao
     * não carrega (não conhece o agregado que a contém). Mesmo padrão de
     * DiscursoRepository::sessaoIdDoDiscurso — usado para enriquecer
     * leituras que precisam saber a quem uma Sessao pertence (ex.:
     * localizar o Sujeito de uma MemoriaLongitudinal a partir de uma de
     * suas sessões) sem exigir um repositório dedicado só para essa
     * consulta.
     */
    public function sujeitoIdDaSessao(string $sessaoId): ?string;
}