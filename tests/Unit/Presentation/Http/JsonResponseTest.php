<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Http;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Http\JsonResponse;

final class JsonResponseTest extends TestCase
{
    public function testSucessoProduzOEnvelopePadrao(): void
    {
        $response = JsonResponse::sucesso(['id' => 'x'], 201);

        self::assertSame(201, $response->status());
        self::assertSame(
            ['success' => true, 'data' => ['id' => 'x']],
            json_decode($response->corpo(), true)
        );
        self::assertSame('application/json; charset=utf-8', $response->headers()['Content-Type']);
    }

    public function testErroProduzOEnvelopePadrao(): void
    {
        $response = JsonResponse::erro('Falhou', 422, ['campo obrigatório']);

        self::assertSame(422, $response->status());
        self::assertSame(
            ['success' => false, 'message' => 'Falhou', 'errors' => ['campo obrigatório']],
            json_decode($response->corpo(), true)
        );
    }

    public function testSemConteudoRetorna204ComCorpoVazio(): void
    {
        $response = JsonResponse::semConteudo();

        self::assertSame(204, $response->status());
        self::assertSame('', $response->corpo());
    }
}
