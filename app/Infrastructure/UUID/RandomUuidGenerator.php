<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\UUID;

use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;

/**
 * Implementação concreta de UuidGeneratorInterface via UUID v4 aleatório
 * (RFC 4122), usando random_bytes como fonte de aleatoriedade — sem
 * dependências externas, conforme "uma tecnologia de cada vez" adotado
 * desde a Sprint 8 para as demais portas de Infraestrutura.
 */
final class RandomUuidGenerator implements UuidGeneratorInterface
{
    public function generate(): string
    {
        $bytes = random_bytes(16);

        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);

        $hex = bin2hex($bytes);

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12)
        );
    }
}
