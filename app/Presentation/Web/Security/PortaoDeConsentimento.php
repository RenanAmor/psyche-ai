<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Security;

use Closure;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;

/**
 * Segundo portão sobre a superfície do Participante, independente de
 * `PortaoDeParticipante` (que só verifica identidade/login): garante que
 * ninguém alcance `GET /conversa` — onde `ConversaController::iniciar()` já
 * cria uma Sessao e a view solicita acesso ao microfone — sem antes passar
 * pela tela de explicação/consentimento (`/conversa/consentimento`). Mesma
 * chave de sessão nativa do PHP, sem entidade de Domínio nem persistência
 * própria, mesmo padrão de `PortaoDeParticipante::proteger()`.
 *
 * Aceite fica só nesta sessão de navegador (não numa tabela) porque este é
 * o piloto técnico do TCLE em docs/pesquisa/tcle-piloto-tecnico-eco.md, não
 * ainda uma pesquisa formalmente aprovada — se/quando isso mudar, vale
 * persistir o aceite por Participante em vez de por sessão de navegador.
 */
final class PortaoDeConsentimento
{
    private const CHAVE_SESSAO = 'psyche_conversa_consentimento_aceito';
    private const ROTA_CONSENTIMENTO = '/conversa/consentimento';

    public static function estaAceito(): bool
    {
        return ($_SESSION[self::CHAVE_SESSAO] ?? false) === true;
    }

    public static function aceitar(): void
    {
        $_SESSION[self::CHAVE_SESSAO] = true;
    }

    /**
     * @param callable(Request): Response $handler
     */
    public static function proteger(callable $handler): Closure
    {
        return static function (Request $request) use ($handler): Response {
            if (!self::estaAceito()) {
                return Response::redirecionar(self::ROTA_CONSENTIMENTO);
            }

            return $handler($request);
        };
    }
}
