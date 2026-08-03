<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;
use PsycheAI\Presentation\Web\Security\PortaoDeParticipante;

/**
 * Tela de entrada/saída do Portão da ECO — não protegida por
 * `PortaoDeParticipante::proteger()`, é justamente a porta de acesso a ele.
 * Mesmo padrão de `AutenticacaoAnalistaController`: a verificação de
 * credencial não acontece aqui, este Controller chama
 * `POST /auth/participante/login` na API REST e só abre a sessão do Portão
 * se a API confirmar a conta real do participante. Ao contrário do Portão
 * do Analista, o sucesso redireciona para `/conversa` (a ECO), não para o
 * Dashboard.
 */
final class AutenticacaoParticipanteController
{
    private const ROTA = '/participante/entrar';
    private const MENSAGEM_CREDENCIAIS_INVALIDAS = 'E-mail ou senha inválidos.';
    private const MENSAGEM_INSCRITO_NA_LISTA_DE_ESPERA = 'Você entrou na lista de espera! Avisaremos por e-mail assim que houver vaga.';
    private const MENSAGEM_ERRO_LISTA_DE_ESPERA = 'Não foi possível registrar seu e-mail na lista de espera. Tente novamente.';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ViewRenderer $viewRenderer = new ViewRenderer()
    ) {
    }

    /**
     * Além do formulário de login, esta tela é o ponto de atrito exato de
     * quem tenta acessar a ECO sem conta (sem autocadastro público) — por
     * isso também reflete o resultado do cadastro na lista de espera
     * (`ListaDeEsperaController::inscrever()`), repassado via query string
     * após o redirect de `POST /lista-espera`.
     */
    public function entrar(Request $request): Response
    {
        [$mensagemInscricao, $tipoInscricao] = match (true) {
            $request->query('inscrito') === '1' => [self::MENSAGEM_INSCRITO_NA_LISTA_DE_ESPERA, 'sucesso'],
            $request->query('erro_lista_espera') === '1' => [self::MENSAGEM_ERRO_LISTA_DE_ESPERA, 'erro'],
            default => [null, null],
        };

        return $this->renderizarFormulario(null, $mensagemInscricao, $tipoInscricao);
    }

    public function autenticar(Request $request): Response
    {
        $email = (string) $request->input('email', '');
        $senha = (string) $request->input('senha', '');

        $resposta = $this->httpClient->post('auth/participante/login', ['email' => $email, 'senha' => $senha]);

        if (!$resposta->sucesso) {
            return Response::erroValidacao($this->renderizarFormularioHtml(self::MENSAGEM_CREDENCIAIS_INVALIDAS));
        }

        /** @var string $participanteId */
        $participanteId = $resposta->dados['id'];

        PortaoDeParticipante::abrirSessao($participanteId);

        return Response::redirecionar('/conversa');
    }

    public function sair(Request $request): Response
    {
        PortaoDeParticipante::sair();

        return Response::redirecionar(self::ROTA);
    }

    private function renderizarFormulario(
        ?string $erro = null,
        ?string $mensagemInscricao = null,
        ?string $tipoInscricao = null
    ): Response {
        return Response::ok($this->renderizarFormularioHtml($erro, $mensagemInscricao, $tipoInscricao));
    }

    private function renderizarFormularioHtml(
        ?string $erro = null,
        ?string $mensagemInscricao = null,
        ?string $tipoInscricao = null
    ): string {
        return $this->viewRenderer->renderComLayout(
            'autenticacao/entrar-participante',
            ['erro' => $erro, 'mensagemInscricao' => $mensagemInscricao, 'tipoInscricao' => $tipoInscricao],
            'Entrar',
            self::ROTA,
            'layout-eco'
        );
    }
}
