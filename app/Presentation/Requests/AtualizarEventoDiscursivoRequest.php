<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

final class AtualizarEventoDiscursivoRequest extends HttpRequestData
{
    public function __construct(
        public readonly string $conteudo,
        public readonly int $posicao
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            conteudo: self::exigirString($dados, 'conteudo'),
            posicao: self::exigirInteiro($dados, 'posicao')
        );
    }
}
