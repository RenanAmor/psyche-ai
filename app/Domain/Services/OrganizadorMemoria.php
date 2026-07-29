<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Services;

use PsycheAI\Domain\Contracts\DomainServiceInterface;
use PsycheAI\Domain\Entities\Sessao;

final class OrganizadorMemoria implements DomainServiceInterface
{
    /**
     * @param Sessao[] $sessoes
     * @return Sessao[]
     */
    public function organizar(array $sessoes): array
    {
        usort(
            $sessoes,
            static fn (Sessao $a, Sessao $b): int =>
                $a->data()->valor() <=> $b->data()->valor()
        );

        return $sessoes;
    }
}