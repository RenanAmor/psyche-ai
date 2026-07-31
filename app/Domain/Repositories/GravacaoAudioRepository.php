<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Repositories;

use PsycheAI\Domain\Contracts\RepositoryInterface;
use PsycheAI\Domain\Entities\GravacaoAudio;

interface GravacaoAudioRepository extends RepositoryInterface
{
    public function findById(string $id): ?GravacaoAudio;

    /**
     * @return GravacaoAudio[]
     */
    public function findBySessaoId(string $sessaoId): array;

    /**
     * @return GravacaoAudio[]
     */
    public function findPendentesDeTranscricao(): array;

    public function save(GravacaoAudio $gravacao): void;
}
