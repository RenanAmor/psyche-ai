<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use PHPUnit\Framework\TestCase;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Application\UseCases\CadastrarSujeito\CadastrarSujeitoCommand;
use PsycheAI\Application\UseCases\CadastrarSujeito\CadastrarSujeitoHandler;

final class CadastrarSujeitoHandlerTest extends TestCase
{
    public function testCadastraSujeitoComSucesso(): void
    {
        $handler = new CadastrarSujeitoHandler();

        $result = $handler->handle(new CadastrarSujeitoCommand('1', 'Sujeito Um'));

        $this->assertSame('1', $result->sujeito()->id()->valor());
        $this->assertSame('Sujeito Um', $result->sujeito()->nome()->valor());

        $dto = $result->dto();
        $this->assertSame('1', $dto->id);
        $this->assertSame('Sujeito Um', $dto->nome);
        $this->assertSame(0, $dto->quantidadeDeSessoes);
    }

    public function testLancaComandoInvalidoQuandoNomeVazio(): void
    {
        $handler = new CadastrarSujeitoHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new CadastrarSujeitoCommand('1', '   '));
    }

    public function testLancaComandoInvalidoQuandoIdVazio(): void
    {
        $handler = new CadastrarSujeitoHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new CadastrarSujeitoCommand('', 'Sujeito Um'));
    }
}
