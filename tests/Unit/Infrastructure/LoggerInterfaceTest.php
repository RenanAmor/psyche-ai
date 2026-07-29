<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Contracts\LoggerInterface;

final class LoggerInterfaceTest extends TestCase
{
    public function testLevelMethodsDelegateToLogWithTheMatchingLevel(): void
    {
        $logger = new class implements LoggerInterface {
            /** @var array<int, array{level: string, message: string, context: array<string, mixed>}> */
            public array $entries = [];

            public function emergency(string $message, array $context = []): void
            {
                $this->log('emergency', $message, $context);
            }

            public function alert(string $message, array $context = []): void
            {
                $this->log('alert', $message, $context);
            }

            public function critical(string $message, array $context = []): void
            {
                $this->log('critical', $message, $context);
            }

            public function error(string $message, array $context = []): void
            {
                $this->log('error', $message, $context);
            }

            public function warning(string $message, array $context = []): void
            {
                $this->log('warning', $message, $context);
            }

            public function notice(string $message, array $context = []): void
            {
                $this->log('notice', $message, $context);
            }

            public function info(string $message, array $context = []): void
            {
                $this->log('info', $message, $context);
            }

            public function debug(string $message, array $context = []): void
            {
                $this->log('debug', $message, $context);
            }

            public function log(string $level, string $message, array $context = []): void
            {
                $this->entries[] = ['level' => $level, 'message' => $message, 'context' => $context];
            }
        };

        $logger->error('falha ao processar sessão', ['sessaoId' => '1']);

        $this->assertSame(
            [['level' => 'error', 'message' => 'falha ao processar sessão', 'context' => ['sessaoId' => '1']]],
            $logger->entries
        );
    }
}
