<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Contracts\TransactionInterface;

final class TransactionInterfaceTest extends TestCase
{
    public function testImplementationTracksBeginCommitAndRollback(): void
    {
        $transaction = new class implements TransactionInterface {
            /** @var array<int, string> */
            public array $events = [];

            public function begin(): void
            {
                $this->events[] = 'begin';
            }

            public function commit(): void
            {
                $this->events[] = 'commit';
            }

            public function rollback(): void
            {
                $this->events[] = 'rollback';
            }
        };

        $transaction->begin();
        $transaction->commit();

        $this->assertSame(['begin', 'commit'], $transaction->events);
    }

    public function testImplementationSupportsRollback(): void
    {
        $transaction = new class implements TransactionInterface {
            /** @var array<int, string> */
            public array $events = [];

            public function begin(): void
            {
                $this->events[] = 'begin';
            }

            public function commit(): void
            {
                $this->events[] = 'commit';
            }

            public function rollback(): void
            {
                $this->events[] = 'rollback';
            }
        };

        $transaction->begin();
        $transaction->rollback();

        $this->assertSame(['begin', 'rollback'], $transaction->events);
    }
}
