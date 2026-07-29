<?php

declare(strict_types=1);

namespace PsycheAI\Infrastructure\Contracts;

/**
 * Porta de registro de logs, com níveis inspirados na RFC 5424 (a mesma
 * base da PSR-3), definida localmente para não introduzir uma dependência
 * externa nesta Sprint.
 */
interface LoggerInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function emergency(string $message, array $context = []): void;

    /**
     * @param array<string, mixed> $context
     */
    public function alert(string $message, array $context = []): void;

    /**
     * @param array<string, mixed> $context
     */
    public function critical(string $message, array $context = []): void;

    /**
     * @param array<string, mixed> $context
     */
    public function error(string $message, array $context = []): void;

    /**
     * @param array<string, mixed> $context
     */
    public function warning(string $message, array $context = []): void;

    /**
     * @param array<string, mixed> $context
     */
    public function notice(string $message, array $context = []): void;

    /**
     * @param array<string, mixed> $context
     */
    public function info(string $message, array $context = []): void;

    /**
     * @param array<string, mixed> $context
     */
    public function debug(string $message, array $context = []): void;

    /**
     * @param array<string, mixed> $context
     */
    public function log(string $level, string $message, array $context = []): void;
}
