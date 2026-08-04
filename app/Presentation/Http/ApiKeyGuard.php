<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Http;

use Closure;

/**
 * Protege uma rota da API REST com uma chave interna compartilhada
 * (cabeçalho `X-Internal-Api-Key`) — hoje usada só em `GET /waitlist`
 * (achado de segurança: esse endpoint devolvia nome/e-mail/texto livre de
 * toda a lista de espera sem exigir nenhuma credencial). Mesmo espírito
 * de `PortaoDeAnalista` do lado Web (proteger um handler existente), mas
 * a API não tem sessão de navegador — por isso é comparação de segredo
 * compartilhado, não cookie de sessão.
 *
 * "Fail closed" de propósito: sem `PSYCHEAI_API_INTERNAL_KEY` configurada
 * (`$chaveEsperada === null`), NUNCA autoriza — diferente de Cors, onde
 * "sem configuração" preserva o comportamento antigo com segurança. Aqui
 * o comportamento antigo era exatamente o problema.
 */
final class ApiKeyGuard
{
    private const CABECALHO = 'X-Internal-Api-Key';

    public function __construct(
        private readonly ?string $chaveEsperada
    ) {
    }

    public function autorizado(Request $request): bool
    {
        if ($this->chaveEsperada === null || $this->chaveEsperada === '') {
            return false;
        }

        $chaveRecebida = $request->cabecalho(self::CABECALHO);

        return $chaveRecebida !== null && hash_equals($this->chaveEsperada, $chaveRecebida);
    }

    /**
     * @param callable(Request, array<string, string>): Response $handler
     */
    public function proteger(callable $handler): Closure
    {
        return function (Request $request, array $params = []) use ($handler): Response {
            if (!$this->autorizado($request)) {
                return JsonResponse::erro('Não autorizado.', 401);
            }

            return $handler($request, $params);
        };
    }
}
