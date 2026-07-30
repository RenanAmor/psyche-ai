<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Security;

use Closure;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;

/**
 * Separa os dois públicos da interface web (revisão pós-Sprint 16, ver
 * docs/Roadmap.md): o Sujeito que fala em `/conversa*` nunca vê esta
 * camada — ela só protege as telas de coleta/análise de dados
 * (`/`, `/sujeitos*`, `/sessoes*`, `/discursos*`, `/memorias*`,
 * `/eventos-discursivos`).
 *
 * Deliberadamente sem entidade de Domínio e sem persistência própria: é
 * um portão de sessão simples, comparando a senha recebida com
 * `PSYCHEAI_SENHA_ANALISTA` via `hash_equals()` (comparação em tempo
 * constante). A chave de sessão `psyche_analista_autenticado` (distinta
 * de `psyche_pessoa_id`/`psyche_conversa_sessao_id`, usadas pelo
 * Sujeito) é deliberada: a Sprint 18 (Plataforma, contas reais) pode
 * apagar esta classe inteira sem nenhuma dívida de migração.
 *
 * API REST (`Presentation/Routes.php`) não é protegida por este portão
 * nesta passada — só é chamada servidor-a-servidor por `ApiHttpClient`,
 * nunca direto pelo navegador na topologia atual.
 */
final class PortaoDeAnalista
{
    private const CHAVE_SESSAO = 'psyche_analista_autenticado';
    private const ROTA_ENTRAR = '/entrar';

    public static function estaAutenticado(): bool
    {
        return ($_SESSION[self::CHAVE_SESSAO] ?? false) === true;
    }

    public static function autenticar(string $senha): bool
    {
        $senhaEsperada = (string) (getenv('PSYCHEAI_SENHA_ANALISTA') ?: '');

        if ($senhaEsperada === '' || !hash_equals($senhaEsperada, $senha)) {
            return false;
        }

        $_SESSION[self::CHAVE_SESSAO] = true;

        return true;
    }

    public static function sair(): void
    {
        unset($_SESSION[self::CHAVE_SESSAO]);
    }

    /**
     * @param callable(Request): Response $handler
     */
    public static function proteger(callable $handler): Closure
    {
        return static function (Request $request) use ($handler): Response {
            if (!self::estaAutenticado()) {
                return Response::redirecionar(self::ROTA_ENTRAR);
            }

            return $handler($request);
        };
    }
}
