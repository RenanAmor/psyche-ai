<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Http;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Application\Exceptions\RecursoNaoEncontradoException;
use PsycheAI\Presentation\Http\ExceptionHandler;
use PsycheAI\Presentation\Http\HttpException;
use RuntimeException;

final class ExceptionHandlerTest extends TestCase
{
    public function testHttpExceptionPreservaOProprioStatusCode(): void
    {
        $response = ExceptionHandler::converter(HttpException::conflito('já existe'));

        self::assertSame(409, $response->status());
    }

    public function testRecursoNaoEncontradoVira404(): void
    {
        $response = ExceptionHandler::converter(RecursoNaoEncontradoException::paraId('Sessao', 'x'));

        self::assertSame(404, $response->status());
    }

    public function testComandoInvalidoVira422(): void
    {
        $response = ExceptionHandler::converter(
            ComandoInvalidoException::fromInvalidArgument(new InvalidArgumentException('inválido'))
        );

        self::assertSame(422, $response->status());
    }

    public function testInvalidArgumentExceptionCruaVira422(): void
    {
        $response = ExceptionHandler::converter(new InvalidArgumentException('inválido'));

        self::assertSame(422, $response->status());
    }

    public function testExcecaoDesconhecidaVira500SemVazarDetalhes(): void
    {
        $response = ExceptionHandler::converter(new RuntimeException('detalhe interno sensível'));

        self::assertSame(500, $response->status());
        self::assertSame(
            'Erro interno do servidor.',
            json_decode($response->corpo(), true)['message']
        );
    }
}
