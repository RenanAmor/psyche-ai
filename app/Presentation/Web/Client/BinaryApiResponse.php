<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Client;

use PsycheAI\Presentation\Web\Errors\ErrorViewModel;

/**
 * Envelope de resposta binária (Sprint 22, captura de áudio): simétrico a
 * ApiResponse, mas carrega bytes crus com seu Content-Type em vez de um
 * array já decodificado do JSON — usado só pela reprodução do áudio
 * original ao analista, que nunca é JSON.
 */
final class BinaryApiResponse
{
    private function __construct(
        public readonly bool $sucesso,
        public readonly string $bytes = '',
        public readonly ?string $contentType = null,
        public readonly ?ErrorViewModel $erro = null
    ) {
    }

    public static function sucesso(string $bytes, ?string $contentType): self
    {
        return new self(true, $bytes, $contentType);
    }

    public static function falha(ErrorViewModel $erro): self
    {
        return new self(false, erro: $erro);
    }
}
