<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Infrastructure\UUID;

use PHPUnit\Framework\TestCase;
use PsycheAI\Infrastructure\Contracts\UuidGeneratorInterface;
use PsycheAI\Infrastructure\UUID\RandomUuidGenerator;

final class RandomUuidGeneratorTest extends TestCase
{
    public function testImplementaUuidGeneratorInterface(): void
    {
        $this->assertInstanceOf(UuidGeneratorInterface::class, new RandomUuidGenerator());
    }

    public function testGeraUmIdentificadorNoFormatoUuidV4(): void
    {
        $id = (new RandomUuidGenerator())->generate();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            $id
        );
    }

    public function testCadaChamadaGeraUmIdentificadorDiferente(): void
    {
        $gerador = new RandomUuidGenerator();

        $this->assertNotSame($gerador->generate(), $gerador->generate());
    }
}
