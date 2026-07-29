<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;

final class UuidGeneratorInterfaceTest extends TestCase
{
    public function testImplementationGeneratesNonEmptyUniqueIdentifiers(): void
    {
        $generator = new class implements UuidGeneratorInterface {
            private int $sequence = 0;

            public function generate(): string
            {
                return 'uuid-' . ++$this->sequence;
            }
        };

        $first = $generator->generate();
        $second = $generator->generate();

        $this->assertNotSame('', $first);
        $this->assertNotSame($first, $second);
    }
}
