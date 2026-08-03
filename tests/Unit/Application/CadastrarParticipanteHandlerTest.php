<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use PHPUnit\Framework\TestCase;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Application\UseCases\CadastrarParticipante\CadastrarParticipanteCommand;
use PsycheAI\Application\UseCases\CadastrarParticipante\CadastrarParticipanteHandler;

final class CadastrarParticipanteHandlerTest extends TestCase
{
    public function testCadastraParticipanteComSucesso(): void
    {
        $handler = new CadastrarParticipanteHandler();

        $result = $handler->handle(new CadastrarParticipanteCommand('1', 'participante@psyche.ai', 'segredo'));

        $this->assertSame('1', $result->participante()->id()->valor());
        $this->assertSame('participante@psyche.ai', $result->participante()->email()->valor());
        $this->assertTrue($result->participante()->verificarSenha('segredo'));

        $dto = $result->dto();
        $this->assertSame('1', $dto->id);
        $this->assertSame('participante@psyche.ai', $dto->email);
    }

    public function testLancaComandoInvalidoQuandoEmailInvalido(): void
    {
        $handler = new CadastrarParticipanteHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new CadastrarParticipanteCommand('1', 'nao-e-email', 'segredo'));
    }

    public function testLancaComandoInvalidoQuandoIdVazio(): void
    {
        $handler = new CadastrarParticipanteHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new CadastrarParticipanteCommand('', 'participante@psyche.ai', 'segredo'));
    }

    public function testLancaComandoInvalidoQuandoSenhaVazia(): void
    {
        $handler = new CadastrarParticipanteHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new CadastrarParticipanteCommand('1', 'participante@psyche.ai', '   '));
    }
}
