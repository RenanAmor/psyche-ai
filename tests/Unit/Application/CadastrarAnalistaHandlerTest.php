<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use PHPUnit\Framework\TestCase;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Application\UseCases\CadastrarAnalista\CadastrarAnalistaCommand;
use PsycheAI\Application\UseCases\CadastrarAnalista\CadastrarAnalistaHandler;

final class CadastrarAnalistaHandlerTest extends TestCase
{
    public function testCadastraAnalistaComSucesso(): void
    {
        $handler = new CadastrarAnalistaHandler();

        $result = $handler->handle(new CadastrarAnalistaCommand('1', 'analista@psyche.ai', 'segredo'));

        $this->assertSame('1', $result->analista()->id()->valor());
        $this->assertSame('analista@psyche.ai', $result->analista()->email()->valor());
        $this->assertTrue($result->analista()->verificarSenha('segredo'));

        $dto = $result->dto();
        $this->assertSame('1', $dto->id);
        $this->assertSame('analista@psyche.ai', $dto->email);
    }

    public function testLancaComandoInvalidoQuandoEmailInvalido(): void
    {
        $handler = new CadastrarAnalistaHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new CadastrarAnalistaCommand('1', 'nao-e-email', 'segredo'));
    }

    public function testLancaComandoInvalidoQuandoIdVazio(): void
    {
        $handler = new CadastrarAnalistaHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new CadastrarAnalistaCommand('', 'analista@psyche.ai', 'segredo'));
    }

    public function testLancaComandoInvalidoQuandoSenhaVazia(): void
    {
        $handler = new CadastrarAnalistaHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new CadastrarAnalistaCommand('1', 'analista@psyche.ai', '   '));
    }
}
