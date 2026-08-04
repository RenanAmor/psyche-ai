<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Repositories;

use PsycheAI\Domain\Contracts\RepositoryInterface;
use PsycheAI\Domain\Entities\AnotacaoSessao;

interface AnotacaoSessaoRepository extends RepositoryInterface
{
    public function findBySessaoId(string $sessaoId): ?AnotacaoSessao;

    public function save(AnotacaoSessao $anotacao): void;
}
