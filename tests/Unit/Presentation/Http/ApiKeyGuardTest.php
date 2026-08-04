<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Http;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Http\ApiKeyGuard;
use PsycheAI\Presentation\Http\JsonResponse;
use PsycheAI\Presentation\Http\Request;

final class ApiKeyGuardTest extends TestCase
{
    public function testAutorizaQuandoCabecalhoBateComAChaveConfigurada(): void
    {
        $guard = new ApiKeyGuard('chave-certa');

        $request = Request::criar('GET', '/waitlist', headers: ['X-Internal-Api-Key' => 'chave-certa']);

        self::assertTrue($guard->autorizado($request));
    }

    public function testNegaQuandoCabecalhoNaoBateComAChaveConfigurada(): void
    {
        $guard = new ApiKeyGuard('chave-certa');

        $request = Request::criar('GET', '/waitlist', headers: ['X-Internal-Api-Key' => 'chave-errada']);

        self::assertFalse($guard->autorizado($request));
    }

    public function testNegaQuandoCabecalhoAusente(): void
    {
        $guard = new ApiKeyGuard('chave-certa');

        self::assertFalse($guard->autorizado(Request::criar('GET', '/waitlist')));
    }

    /**
     * "Fail closed": ausência de configuração nunca deve virar acesso
     * liberado — diferente do padrão usado em Cors (onde config vazia é
     * um no-op seguro).
     */
    public function testNegaSempreQuandoChaveEsperadaNaoConfigurada(): void
    {
        $guard = new ApiKeyGuard(null);

        $request = Request::criar('GET', '/waitlist', headers: ['X-Internal-Api-Key' => 'qualquer-coisa']);

        self::assertFalse($guard->autorizado($request));
    }

    public function testProtegerChamaOHandlerQuandoAutorizado(): void
    {
        $guard = new ApiKeyGuard('chave-certa');
        $protegido = $guard->proteger(fn (Request $r, array $p): JsonResponse => JsonResponse::sucesso(['ok' => true]));

        $response = $protegido(Request::criar('GET', '/waitlist', headers: ['X-Internal-Api-Key' => 'chave-certa']), []);

        self::assertSame(200, $response->status());
    }

    public function testProtegerDevolve401SemChamarOHandlerQuandoNaoAutorizado(): void
    {
        $guard = new ApiKeyGuard('chave-certa');
        $handlerChamado = false;
        $protegido = $guard->proteger(function (Request $r, array $p) use (&$handlerChamado): JsonResponse {
            $handlerChamado = true;

            return JsonResponse::sucesso([]);
        });

        $response = $protegido(Request::criar('GET', '/waitlist'), []);

        self::assertSame(401, $response->status());
        self::assertFalse($handlerChamado);
    }
}
