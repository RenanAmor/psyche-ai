<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Analista;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;

final class AnalistaTest extends TestCase
{
    public function testExposesIdEEmail(): void
    {
        $analista = new Analista(
            new Identificador('1'),
            new Email('analista@psyche.ai'),
            password_hash('segredo', PASSWORD_DEFAULT),
            new DateTimeImmutable('2026-07-30 10:00:00')
        );

        $this->assertSame('1', $analista->id()->valor());
        $this->assertSame('analista@psyche.ai', $analista->email()->valor());
        $this->assertSame('2026-07-30 10:00:00', $analista->criadoEm()->format('Y-m-d H:i:s'));
    }

    public function testVerificarSenhaComSenhaCorretaRetornaTrue(): void
    {
        $analista = new Analista(
            new Identificador('1'),
            new Email('analista@psyche.ai'),
            password_hash('segredo', PASSWORD_DEFAULT),
            new DateTimeImmutable()
        );

        $this->assertTrue($analista->verificarSenha('segredo'));
    }

    public function testVerificarSenhaComSenhaIncorretaRetornaFalse(): void
    {
        $analista = new Analista(
            new Identificador('1'),
            new Email('analista@psyche.ai'),
            password_hash('segredo', PASSWORD_DEFAULT),
            new DateTimeImmutable()
        );

        $this->assertFalse($analista->verificarSenha('senha-errada'));
    }

    public function testSenhaHashNuncaExpoeATextoPlano(): void
    {
        $analista = new Analista(
            new Identificador('1'),
            new Email('analista@psyche.ai'),
            password_hash('segredo', PASSWORD_DEFAULT),
            new DateTimeImmutable()
        );

        $this->assertStringNotContainsString('segredo', $analista->senhaHash());
    }
}
