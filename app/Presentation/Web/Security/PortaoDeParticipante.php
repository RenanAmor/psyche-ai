<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Security;

use Closure;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;

/**
 * Portão da ECO (`/conversa*`) — sistema de autenticação totalmente
 * independente de `PortaoDeAnalista`: chaves de sessão próprias
 * (`psyche_participante_autenticado`/`psyche_participante_id`, distintas de
 * `psyche_analista_autenticado`/`psyche_analista_id` e de
 * `psyche_conversa_sessao_id`), sem qualquer leitura/escrita cruzada entre
 * os dois portões. Substitui a identidade por cookie anônimo
 * (`psyche_pessoa_id`, Sprint 17) e o login por troca de cookie da Sprint
 * 20 — a ECO agora exige uma conta real, provisionada pelo Analista
 * (`bin/criar-participante.php`, sem autocadastro público).
 *
 * Mesmo padrão de `PortaoDeAnalista`: sem entidade de Domínio nem
 * persistência própria, só abre/fecha/consulta a sessão. Quem verifica a
 * senha é a API REST, via `POST /auth/participante/login`
 * (`ParticipanteApplicationService::autenticar()`) —
 * `AutenticacaoParticipanteController` (Web) chama a API e só então abre a
 * sessão aqui.
 */
final class PortaoDeParticipante
{
    private const CHAVE_SESSAO = 'psyche_participante_autenticado';
    private const CHAVE_PARTICIPANTE_ID = 'psyche_participante_id';
    private const ROTA_ENTRAR = '/participante/entrar';

    public static function estaAutenticado(): bool
    {
        return ($_SESSION[self::CHAVE_SESSAO] ?? false) === true;
    }

    public static function abrirSessao(string $participanteId): void
    {
        $_SESSION[self::CHAVE_SESSAO] = true;
        $_SESSION[self::CHAVE_PARTICIPANTE_ID] = $participanteId;
    }

    public static function participanteId(): ?string
    {
        $id = $_SESSION[self::CHAVE_PARTICIPANTE_ID] ?? null;

        return is_string($id) ? $id : null;
    }

    public static function sair(): void
    {
        unset($_SESSION[self::CHAVE_SESSAO], $_SESSION[self::CHAVE_PARTICIPANTE_ID]);
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
