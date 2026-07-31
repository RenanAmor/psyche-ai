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
 * Sem entidade de Domínio nem persistência própria — permanece um portão
 * de sessão simples. Desde a Sprint 18 (Plataforma), a verificação de
 * credencial em si não é mais responsabilidade desta classe: ela só
 * abre/fecha/consulta a sessão (`psyche_analista_autenticado`, distinta
 * de `psyche_pessoa_id`/`psyche_conversa_sessao_id`, usadas pelo
 * Sujeito). Quem verifica a senha é a API REST, via
 * `POST /auth/login` (`AnalistaApplicationService::autenticar()`,
 * contas reais em vez da antiga `PSYCHEAI_SENHA_ANALISTA` única) —
 * `AutenticacaoAnalistaController` chama a API e só então abre a sessão
 * aqui.
 *
 * API REST (`Presentation/Routes.php`) não é protegida por este portão
 * nesta passada — só é chamada servidor-a-servidor por `ApiHttpClient`,
 * nunca direto pelo navegador na topologia atual.
 */
final class PortaoDeAnalista
{
    private const CHAVE_SESSAO = 'psyche_analista_autenticado';
    private const CHAVE_ANALISTA_ID = 'psyche_analista_id';
    private const ROTA_ENTRAR = '/entrar';

    public static function estaAutenticado(): bool
    {
        return ($_SESSION[self::CHAVE_SESSAO] ?? false) === true;
    }

    public static function abrirSessao(string $analistaId): void
    {
        $_SESSION[self::CHAVE_SESSAO] = true;
        $_SESSION[self::CHAVE_ANALISTA_ID] = $analistaId;
    }

    public static function analistaId(): ?string
    {
        $id = $_SESSION[self::CHAVE_ANALISTA_ID] ?? null;

        return is_string($id) ? $id : null;
    }

    public static function sair(): void
    {
        unset($_SESSION[self::CHAVE_SESSAO], $_SESSION[self::CHAVE_ANALISTA_ID]);
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
