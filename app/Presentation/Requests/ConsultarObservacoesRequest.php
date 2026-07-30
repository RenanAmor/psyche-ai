<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

/**
 * Parâmetro de consulta de GET /subjects/{id}/observations: opcional,
 * diferente dos Requests de escrita (HttpRequestData::exigir*) — a
 * ausência do filtro simplesmente significa "usar o mínimo padrão do
 * detector de recorrências" (RecorrenciaMinimaSpecification), nunca um
 * erro.
 *
 * `vocabulario=lacan` (Sprint 16) é igualmente opcional: sua ausência
 * significa devolver só a leitura do Motor Freud, sem o rótulo do Motor
 * Lacan ao lado.
 */
final class ConsultarObservacoesRequest extends HttpRequestData
{
    private function __construct(
        public readonly ?int $minimoDeRecorrencia,
        public readonly bool $comLeituraLacaniana
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        $valor = $dados['minimoDeRecorrencia'] ?? null;
        $minimo = ($valor === null || $valor === '')
            ? null
            : self::exigirInteiro(['minimoDeRecorrencia' => $valor], 'minimoDeRecorrencia');

        $comLeituraLacaniana = ($dados['vocabulario'] ?? null) === 'lacan';

        return new self($minimo, $comLeituraLacaniana);
    }
}
