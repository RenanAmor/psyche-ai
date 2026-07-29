<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Errors;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Errors\ErrorViewModelFactory;

final class ErrorViewModelFactoryTest extends TestCase
{
    public function testComunicacao(): void
    {
        $erro = ErrorViewModelFactory::comunicacao('sujeitos');

        $this->assertSame(ErrorType::COMUNICACAO, $erro->tipo);
        $this->assertSame(502, $erro->statusHttp());
        $this->assertStringContainsString('sujeitos', $erro->mensagem);
    }

    public function testNaoEncontrado(): void
    {
        $erro = ErrorViewModelFactory::naoEncontrado('sessoes');

        $this->assertSame(ErrorType::NAO_ENCONTRADO, $erro->tipo);
        $this->assertSame(404, $erro->statusHttp());
        $this->assertStringContainsString('sessoes', $erro->mensagem);
    }

    public function testValidacaoCarregaDetalhesPorCampo(): void
    {
        $erro = ErrorViewModelFactory::validacao(['nome' => 'obrigatório']);

        $this->assertSame(ErrorType::VALIDACAO, $erro->tipo);
        $this->assertSame(422, $erro->statusHttp());
        $this->assertSame(['nome' => 'obrigatório'], $erro->detalhes);
    }

    public function testInterno(): void
    {
        $erro = ErrorViewModelFactory::interno();

        $this->assertSame(ErrorType::INTERNO, $erro->tipo);
        $this->assertSame(500, $erro->statusHttp());
    }
}
