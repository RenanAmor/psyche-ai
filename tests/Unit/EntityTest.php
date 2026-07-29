<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Entity;

final class EntityTest extends TestCase
{
    public function testEntitiesWithSameIdAreEqual(): void
    {
        $entityA = new class ('1') extends Entity {
        };

        $entityB = new class ('1') extends Entity {
        };

        $this->assertTrue($entityA->equals($entityB));
    }

    public function testEntitiesWithDifferentIdsAreNotEqual(): void
    {
        $entityA = new class ('1') extends Entity {
        };

        $entityB = new class ('2') extends Entity {
        };

        $this->assertFalse($entityA->equals($entityB));
    }
}