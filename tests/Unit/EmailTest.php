<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\ValueObjects\Email;

final class EmailTest extends TestCase
{
    public function testAceitaEExpoeUmEmailValido(): void
    {
        $email = new Email('analista@psyche.ai');

        $this->assertSame('analista@psyche.ai', $email->valor());
        $this->assertSame('analista@psyche.ai', (string) $email);
    }

    #[DataProvider('emailsInvalidos')]
    public function testLancaExcecaoParaEmailInvalido(string $valor): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Email($valor);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function emailsInvalidos(): array
    {
        return [
            'vazio' => [''],
            'sem arroba' => ['analista-psyche.ai'],
            'sem domínio' => ['analista@'],
            'com espaço' => ['analista @psyche.ai'],
        ];
    }
}
