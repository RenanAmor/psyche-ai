<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

final class AtualizarMemoriaRequest extends HttpRequestData
{
    public function __construct(
        public readonly string $sujeitoId
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(sujeitoId: self::exigirString($dados, 'sujeitoId'));
    }
}
