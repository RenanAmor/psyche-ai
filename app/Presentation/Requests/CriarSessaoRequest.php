<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

use DateTimeImmutable;

final class CriarSessaoRequest extends HttpRequestData
{
    public function __construct(
        public readonly string $sujeitoId,
        public readonly string $id,
        public readonly DateTimeImmutable $data
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            sujeitoId: self::exigirString($dados, 'sujeitoId'),
            id: self::exigirString($dados, 'id'),
            data: self::exigirData($dados, 'data')
        );
    }
}
