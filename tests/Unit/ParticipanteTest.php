<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Participante;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;

final class ParticipanteTest extends TestCase
{
    public function testExposesIdEEmail(): void
    {
        $participante = new Participante(
            new Identificador('1'),
            new Email('participante@psyche.ai'),
            password_hash('segredo', PASSWORD_DEFAULT),
            new DateTimeImmutable('2026-07-30 10:00:00')
        );

        $this->assertSame('1', $participante->id()->valor());
        $this->assertSame('participante@psyche.ai', $participante->email()->valor());
        $this->assertSame('2026-07-30 10:00:00', $participante->criadoEm()->format('Y-m-d H:i:s'));
    }

    public function testVerificarSenhaComSenhaCorretaRetornaTrue(): void
    {
        $participante = new Participante(
            new Identificador('1'),
            new Email('participante@psyche.ai'),
            password_hash('segredo', PASSWORD_DEFAULT),
            new DateTimeImmutable()
        );

        $this->assertTrue($participante->verificarSenha('segredo'));
    }

    public function testVerificarSenhaComSenhaIncorretaRetornaFalse(): void
    {
        $participante = new Participante(
            new Identificador('1'),
            new Email('participante@psyche.ai'),
            password_hash('segredo', PASSWORD_DEFAULT),
            new DateTimeImmutable()
        );

        $this->assertFalse($participante->verificarSenha('senha-errada'));
    }

    public function testSenhaHashNuncaExpoeATextoPlano(): void
    {
        $participante = new Participante(
            new Identificador('1'),
            new Email('participante@psyche.ai'),
            password_hash('segredo', PASSWORD_DEFAULT),
            new DateTimeImmutable()
        );

        $this->assertStringNotContainsString('segredo', $participante->senhaHash());
    }
}
