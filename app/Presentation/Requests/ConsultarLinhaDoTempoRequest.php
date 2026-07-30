<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

use DateTimeImmutable;
use PsycheAI\Application\DTOs\LinhaDoTempoItemDTO;
use PsycheAI\Presentation\Http\HttpException;

/**
 * Parâmetros de consulta de GET /subjects/{id}/timeline: todos
 * opcionais, diferente dos Requests de escrita (HttpRequestData::exigir*)
 * — a ausência de um filtro simplesmente significa "não filtrar por
 * este campo", nunca um erro.
 */
final class ConsultarLinhaDoTempoRequest extends HttpRequestData
{
    private const TIPOS_VALIDOS = [
        LinhaDoTempoItemDTO::TIPO_SESSAO,
        LinhaDoTempoItemDTO::TIPO_DISCURSO,
        LinhaDoTempoItemDTO::TIPO_EVENTO,
        LinhaDoTempoItemDTO::TIPO_MEMORIA,
    ];

    private const PADRAO_PAGINA = 1;
    private const PADRAO_POR_PAGINA = 20;
    private const LIMITE_POR_PAGINA = 100;

    public function __construct(
        public readonly ?string $tipo,
        public readonly ?DateTimeImmutable $de,
        public readonly ?DateTimeImmutable $ate,
        public readonly ?string $q,
        public readonly int $pagina,
        public readonly int $porPagina
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        $tipo = self::opcionalString($dados, 'tipo');

        if ($tipo !== null && !in_array($tipo, self::TIPOS_VALIDOS, true)) {
            throw HttpException::badRequest(sprintf(
                'O campo "tipo" deve ser um de: %s.',
                implode(', ', self::TIPOS_VALIDOS)
            ));
        }

        $de = self::opcionalData($dados, 'de');
        $ate = self::opcionalData($dados, 'ate');

        if ($de !== null && $ate !== null && $de > $ate) {
            throw HttpException::badRequest('O campo "de" deve ser anterior ou igual ao campo "ate".');
        }

        $porPagina = self::opcionalInteiroPositivo($dados, 'porPagina', self::PADRAO_POR_PAGINA);

        if ($porPagina > self::LIMITE_POR_PAGINA) {
            throw HttpException::badRequest(sprintf(
                'O campo "porPagina" não pode ser maior que %d.',
                self::LIMITE_POR_PAGINA
            ));
        }

        return new self(
            tipo: $tipo,
            de: $de,
            ate: $ate,
            q: self::opcionalString($dados, 'q'),
            pagina: self::opcionalInteiroPositivo($dados, 'pagina', self::PADRAO_PAGINA),
            porPagina: $porPagina
        );
    }
}
