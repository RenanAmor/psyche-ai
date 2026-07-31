<?php

declare(strict_types=1);

namespace PsycheAI\Transport\DTO;

/**
 * Resultado agregado de uma execução de ProductionTransport::run().
 * `connected` distingue "não deu nem para conectar/autenticar" (falha
 * total, nada foi processado) de uma execução normal em que cada item
 * tem seu próprio resultado individual.
 */
final class TransportRunOutcome
{
    /**
     * @param list<FileTransportResult> $results
     */
    public function __construct(
        public readonly bool $connected,
        public readonly array $results,
    ) {
    }
}
