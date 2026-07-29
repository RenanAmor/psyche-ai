<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Http;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Http\HttpException;

final class HttpExceptionTest extends TestCase
{
    public function testBadRequestCarrega400(): void
    {
        $erro = HttpException::badRequest('Campo ausente', ['id']);

        self::assertSame(400, $erro->statusCode());
        self::assertSame('Campo ausente', $erro->getMessage());
        self::assertSame(['id'], $erro->erros());
    }

    public function testNaoEncontradoCarrega404(): void
    {
        self::assertSame(404, HttpException::naoEncontrado('x')->statusCode());
    }

    public function testConflitoCarrega409(): void
    {
        self::assertSame(409, HttpException::conflito('x')->statusCode());
    }

    public function testNaoProcessavelCarrega422(): void
    {
        self::assertSame(422, HttpException::naoProcessavel('x')->statusCode());
    }
}
