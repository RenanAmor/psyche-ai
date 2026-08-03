<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use PHPUnit\Framework\TestCase;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Application\UseCases\InscreverNaListaDeEspera\InscreverNaListaDeEsperaCommand;
use PsycheAI\Application\UseCases\InscreverNaListaDeEspera\InscreverNaListaDeEsperaHandler;

final class InscreverNaListaDeEsperaHandlerTest extends TestCase
{
    private function criarComando(
        string $id = '1',
        string $email = 'interessado@psyche.ai',
        string $nome = 'Ana Interessada',
        ?string $profissao = 'Psicóloga',
        ?string $instituicao = 'Universidade Federal',
        string $paisEstado = 'Brasil/SP',
        string $motivoInteresse = 'Quero participar da pesquisa.',
        bool $aceitouPoliticaPrivacidade = true,
        bool $aceitouTermoConsentimento = true
    ): InscreverNaListaDeEsperaCommand {
        return new InscreverNaListaDeEsperaCommand(
            $id,
            $email,
            $nome,
            $profissao,
            $instituicao,
            $paisEstado,
            $motivoInteresse,
            $aceitouPoliticaPrivacidade,
            $aceitouTermoConsentimento
        );
    }

    public function testInscreveComSucesso(): void
    {
        $handler = new InscreverNaListaDeEsperaHandler();

        $result = $handler->handle($this->criarComando());

        $this->assertSame('1', $result->inscricao()->id()->valor());
        $this->assertSame('interessado@psyche.ai', $result->inscricao()->email()->valor());
        $this->assertSame('Ana Interessada', $result->inscricao()->nome());

        $dto = $result->dto();
        $this->assertSame('1', $dto->id);
        $this->assertSame('interessado@psyche.ai', $dto->email);
        $this->assertTrue($dto->aceitouPoliticaPrivacidade);
        $this->assertTrue($dto->aceitouTermoConsentimento);
    }

    public function testAceitaProfissaoEInstituicaoNulas(): void
    {
        $handler = new InscreverNaListaDeEsperaHandler();

        $result = $handler->handle($this->criarComando(profissao: null, instituicao: null));

        $this->assertNull($result->inscricao()->profissao());
        $this->assertNull($result->inscricao()->instituicao());
    }

    public function testLancaComandoInvalidoQuandoEmailInvalido(): void
    {
        $handler = new InscreverNaListaDeEsperaHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle($this->criarComando(email: 'nao-e-email'));
    }

    public function testLancaComandoInvalidoQuandoIdVazio(): void
    {
        $handler = new InscreverNaListaDeEsperaHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle($this->criarComando(id: ''));
    }

    public function testLancaComandoInvalidoQuandoNomeVazio(): void
    {
        $handler = new InscreverNaListaDeEsperaHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle($this->criarComando(nome: '   '));
    }

    public function testLancaComandoInvalidoQuandoPaisEstadoVazio(): void
    {
        $handler = new InscreverNaListaDeEsperaHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle($this->criarComando(paisEstado: '   '));
    }

    public function testLancaComandoInvalidoQuandoMotivoInteresseVazio(): void
    {
        $handler = new InscreverNaListaDeEsperaHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle($this->criarComando(motivoInteresse: '   '));
    }

    public function testLancaComandoInvalidoQuandoNaoAceitouAPoliticaDePrivacidade(): void
    {
        $handler = new InscreverNaListaDeEsperaHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle($this->criarComando(aceitouPoliticaPrivacidade: false));
    }

    public function testLancaComandoInvalidoQuandoNaoAceitouOTermoDeConsentimento(): void
    {
        $handler = new InscreverNaListaDeEsperaHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle($this->criarComando(aceitouTermoConsentimento: false));
    }
}
