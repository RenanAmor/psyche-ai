<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

use DateTimeImmutable;

final class AtualizarSessaoRequest extends HttpRequestData
{
    public function __construct(
        public readonly DateTimeImmutable $data
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(data: self::exigirData($dados, 'data'));
    }
}
