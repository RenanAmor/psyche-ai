<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts\DTOs;

final class RecordingDTO
{
    /**
     * @param TrackDTO[] $tracks
     */
    public function __construct(
        public readonly string $recordingId,
        public readonly string $status,
        public readonly array $tracks
    ) {
    }
}
