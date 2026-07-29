<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Contracts\PersistenceInterface;

final class PersistenceInterfaceTest extends TestCase
{
    public function testImplementationSavesFindsAndDeletesRecords(): void
    {
        $persistence = new class implements PersistenceInterface {
            /** @var array<string, array<string, array<string, mixed>>> */
            private array $storage = [];

            public function find(string $collection, string $id): ?array
            {
                return $this->storage[$collection][$id] ?? null;
            }

            public function save(string $collection, string $id, array $data): void
            {
                $this->storage[$collection][$id] = $data;
            }

            public function delete(string $collection, string $id): void
            {
                unset($this->storage[$collection][$id]);
            }

            public function exists(string $collection, string $id): bool
            {
                return isset($this->storage[$collection][$id]);
            }
        };

        $persistence->save('sujeitos', '1', ['nome' => 'Sujeito Um']);

        $this->assertTrue($persistence->exists('sujeitos', '1'));
        $this->assertSame(['nome' => 'Sujeito Um'], $persistence->find('sujeitos', '1'));

        $persistence->delete('sujeitos', '1');

        $this->assertFalse($persistence->exists('sujeitos', '1'));
        $this->assertNull($persistence->find('sujeitos', '1'));
    }
}
