<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;
use PsycheAI\Presentation\Web\Security\PortaoDeConsentimento;

/**
 * Tela de explicação/consentimento entre o login do Participante e a
 * própria ECO (`/conversa`) — protegida apenas por `PortaoDeParticipante`
 * (precisa estar autenticado para chegar aqui), nunca por
 * `PortaoDeConsentimento` (seria a própria porta travando a si mesma).
 * Existe porque, antes desta tela, `GET /conversa` não explicava a nenhum
 * participante que sua fala seria gravada, nem por quê — só a lista de
 * espera (`entrar-participante.php`) tocava nisso, e de forma preliminar.
 */
final class ConsentimentoController
{
    private const ROTA = '/conversa/consentimento';
    private const ROTA_CONVERSA = '/conversa';
    private const MENSAGEM_ACEITE_OBRIGATORIO = 'É preciso marcar que você leu e entendeu para continuar.';

    public function __construct(
        private readonly ViewRenderer $viewRenderer = new ViewRenderer()
    ) {
    }

    public function mostrar(Request $request): Response
    {
        return Response::ok($this->renderizarHtml());
    }

    public function aceitar(Request $request): Response
    {
        $aceitou = (string) $request->input('aceitouExplicacao', '') === '1';

        if (!$aceitou) {
            return Response::erroValidacao($this->renderizarHtml(self::MENSAGEM_ACEITE_OBRIGATORIO));
        }

        PortaoDeConsentimento::aceitar();

        return Response::redirecionar(self::ROTA_CONVERSA);
    }

    private function renderizarHtml(?string $erro = null): string
    {
        return $this->viewRenderer->renderComLayout(
            'conversa/consentimento',
            ['erro' => $erro],
            'Antes de começar',
            self::ROTA,
            'layout-eco'
        );
    }
}
