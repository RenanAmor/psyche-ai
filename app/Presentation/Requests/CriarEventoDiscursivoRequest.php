<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

final class CriarEventoDiscursivoRequest extends HttpRequestData
{
    public function __construct(
        public readonly string $discursoId,
        public readonly string $id,
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
            discursoId: self::exigirString($dados, 'discursoId'),
            id: self::exigirString($dados, 'id'),
            conteudo: self::exigirString($dados, 'conteudo'),
            posicao: self::exigirInteiro($dados, 'posicao')
        );
    }
}
