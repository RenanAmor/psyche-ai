<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\Client;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\Client\ApiResponse;
use PsycheAI\Presentation\Web\Errors\ErrorViewModelFactory;

final class ApiResponseTest extends TestCase
{
    public function testSucessoCarregaDados(): void
    {
        $resposta = ApiResponse::sucesso([['id' => '1']]);

        $this->assertTrue($resposta->sucesso);
        $this->assertSame([['id' => '1']], $resposta->dados);
        $this->assertNull($resposta->erro);
    }

    public function testFalhaCarregaErro(): void
    {
        $erro = ErrorViewModelFactory::interno();
        $resposta = ApiResponse::falha($erro);

        $this->assertFalse($resposta->sucesso);
        $this->assertSame([], $resposta->dados);
        $this->assertSame($erro, $resposta->erro);
    }
}
