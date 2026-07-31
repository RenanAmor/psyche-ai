<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Persistence\SQLite\Repositories;

use PDO;
use PsycheAI\Domain\Entities\GravacaoAudio;
use PsycheAI\Domain\Repositories\GravacaoAudioRepository;

final class SQLiteGravacaoAudioRepository implements GravacaoAudioRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findById(string $id): ?GravacaoAudio
    {
        return GravacaoAudioMapper::findById($this->pdo, $id);
    }

    /**
     * @return GravacaoAudio[]
     */
    public function findBySessaoId(string $sessaoId): array
    {
        return GravacaoAudioMapper::findBySessaoId($this->pdo, $sessaoId);
    }

    /**
     * @return GravacaoAudio[]
     */
    public function findPendentesDeTranscricao(): array
    {
        return GravacaoAudioMapper::findPendentesDeTranscricao($this->pdo);
    }

    public function save(GravacaoAudio $gravacao): void
    {
        GravacaoAudioMapper::save($this->pdo, $gravacao);
    }
}
