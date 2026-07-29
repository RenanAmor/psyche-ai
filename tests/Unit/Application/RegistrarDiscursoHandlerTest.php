<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Application\UseCases\RegistrarDiscurso\RegistrarDiscursoCommand;
use PsycheAI\Application\UseCases\RegistrarDiscurso\RegistrarDiscursoHandler;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;

final class RegistrarDiscursoHandlerTest extends TestCase
{
    public function testRegistraDiscursoEAssociaASessao(): void
    {
        $sessao = new Sessao(new Identificador('s1'), new DataSessao(new DateTimeImmutable()));

        $handler = new RegistrarDiscursoHandler();
        $result = $handler->handle(new RegistrarDiscursoCommand($sessao, 'd1', 'Discurso de teste.'));

        $this->assertSame([$result->discurso()], $sessao->discursos());
        $this->assertSame('d1', $result->dto()->id);
        $this->assertSame('Discurso de teste.', $result->dto()->conteudo);
        $this->assertSame(0, $result->dto()->quantidadeDeEventos);
    }

    public function testLancaComandoInvalidoQuandoConteudoVazio(): void
    {
        $sessao = new Sessao(new Identificador('s1'), new DataSessao(new DateTimeImmutable()));
        $handler = new RegistrarDiscursoHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new RegistrarDiscursoCommand($sessao, 'd1', '   '));
    }
}
