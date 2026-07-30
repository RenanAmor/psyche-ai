<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

/**
 * Parâmetro de consulta de GET /subjects/{id}/observations: opcional,
 * diferente dos Requests de escrita (HttpRequestData::exigir*) — a
 * ausência do filtro simplesmente significa "usar o mínimo padrão do
 * detector de recorrências" (RecorrenciaMinimaSpecification), nunca um
 * erro.
 */
final class ConsultarObservacoesRequest extends HttpRequestData
{
    private function __construct(
        public readonly ?int $minimoDeRecorrencia
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        $valor = $dados['minimoDeRecorrencia'] ?? null;

        if ($valor === null || $valor === '') {
            return new self(null);
        }

        return new self(self::exigirInteiro(['minimoDeRecorrencia' => $valor], 'minimoDeRecorrencia'));
    }
}
