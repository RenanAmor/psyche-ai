<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use DateTimeImmutable;
use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Errors\ErrorViewModel;
use PsycheAI\Presentation\Web\Errors\ErrorViewModelFactory;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;
use PsycheAI\Presentation\Web\ViewModels\MensagemViewModel;

/**
 * Tela de Conversa da Sprint 12: inicia uma Sessao automaticamente (sob
 * um Sujeito "Visitante" padrão, já que não há autenticação nesta
 * Sprint), mantém seu id em $_SESSION nativa do PHP para persistir entre
 * requisições da mesma aba/navegador, e envia mensagens através do único
 * endpoint novo desta Sprint (POST /sessions/{id}/messages). O histórico
 * é reconstruído filtrando GET /events por sessaoId — reaproveitando um
 * endpoint já existente em vez de criar mais um só para leitura.
 *
 * Não estende AbstractResourceController/AbstractCrudResourceController:
 * o ceremonial de listagem/CRUD genérico não se aplica a uma tela de
 * conversa com fluxo próprio de iniciar/enviar.
 */
final class ConversaController
{
    private const SUJEITO_ID_PADRAO = 'visitante';
    private const SUJEITO_NOME_PADRAO = 'Visitante';
    private const CHAVE_SESSAO_ATIVA = 'psyche_conversa_sessao_id';
    private const ROTA = '/conversa';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ViewRenderer $viewRenderer = new ViewRenderer()
    ) {
    }

    public function iniciar(Request $request): Response
    {
        $sessaoId = $this->sessaoAtivaOuNova();

        if ($sessaoId === null) {
            return ErrorController::renderizar(
                ErrorViewModelFactory::comunicacao('sessions'),
                $this->viewRenderer,
                self::ROTA
            );
        }

        return $this->renderizarConversa($sessaoId);
    }

    public function enviar(Request $request): Response
    {
        $conteudo = trim((string) $request->input('conteudo', ''));

        $sessaoId = $this->sessaoAtivaOuNova();

        if ($sessaoId === null) {
            return ErrorController::renderizar(
                ErrorViewModelFactory::comunicacao('sessions'),
                $this->viewRenderer,
                self::ROTA
            );
        }

        if ($conteudo === '') {
            return $this->renderizarConversa(
                $sessaoId,
                'A mensagem não pode ser vazia.',
                'erro',
                $conteudo
            );
        }

        $resposta = $this->httpClient->post('sessions/' . $sessaoId . '/messages', ['conteudo' => $conteudo]);

        if ($resposta->sucesso) {
            return $this->renderizarConversa($sessaoId);
        }

        if ($resposta->erro?->tipo === ErrorType::NAO_ENCONTRADO) {
            return $this->recuperarDeSessaoInexistente($conteudo);
        }

        return $this->renderizarConversa($sessaoId, $this->mensagemErro($resposta->erro), 'erro', $conteudo);
    }

    private function recuperarDeSessaoInexistente(string $conteudo): Response
    {
        $this->limparSessaoAtiva();
        $novaSessaoId = $this->criarNovaSessao();

        if ($novaSessaoId === null) {
            return ErrorController::renderizar(
                ErrorViewModelFactory::comunicacao('sessions'),
                $this->viewRenderer,
                self::ROTA
            );
        }

        $reenvio = $this->httpClient->post('sessions/' . $novaSessaoId . '/messages', ['conteudo' => $conteudo]);

        if (!$reenvio->sucesso) {
            return $this->renderizarConversa($novaSessaoId, $this->mensagemErro($reenvio->erro), 'erro', $conteudo);
        }

        return $this->renderizarConversa(
            $novaSessaoId,
            'Sua conversa anterior não estava mais disponível; uma nova conversa foi iniciada.',
            'info'
        );
    }

    private function renderizarConversa(
        string $sessaoId,
        ?string $alerta = null,
        string $tipoAlerta = 'erro',
        string $valorConteudo = ''
    ): Response {
        $eventosResposta = $this->httpClient->get('events');

        if ($eventosResposta->sucesso) {
            $mensagens = MensagemViewModel::historicoDaSessao($eventosResposta->dados, $sessaoId);
        } else {
            $mensagens = [];
            $alerta ??= $this->mensagemErro($eventosResposta->erro);
        }

        $html = $this->viewRenderer->renderComLayout(
            'conversa/index',
            [
                'mensagens' => $mensagens,
                'alerta' => $alerta,
                'tipoAlerta' => $tipoAlerta,
                'valorConteudo' => $valorConteudo,
            ],
            'Conversa',
            self::ROTA
        );

        return Response::ok($html);
    }

    private function sessaoAtivaOuNova(): ?string
    {
        $sessaoId = $_SESSION[self::CHAVE_SESSAO_ATIVA] ?? null;

        if (is_string($sessaoId) && $sessaoId !== '') {
            return $sessaoId;
        }

        return $this->criarNovaSessao();
    }

    private function criarNovaSessao(): ?string
    {
        if (!$this->garantirSujeitoPadrao()) {
            return null;
        }

        $id = bin2hex(random_bytes(8));

        $resposta = $this->httpClient->post('sessions', [
            'id' => $id,
            'sujeitoId' => self::SUJEITO_ID_PADRAO,
            'data' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
        ]);

        if (!$resposta->sucesso) {
            return null;
        }

        $_SESSION[self::CHAVE_SESSAO_ATIVA] = $id;

        return $id;
    }

    private function garantirSujeitoPadrao(): bool
    {
        $existente = $this->httpClient->get('subjects/' . self::SUJEITO_ID_PADRAO);

        if ($existente->sucesso) {
            return true;
        }

        $criado = $this->httpClient->post('subjects', [
            'id' => self::SUJEITO_ID_PADRAO,
            'nome' => self::SUJEITO_NOME_PADRAO,
        ]);

        return $criado->sucesso;
    }

    private function limparSessaoAtiva(): void
    {
        unset($_SESSION[self::CHAVE_SESSAO_ATIVA]);
    }

    private function mensagemErro(?ErrorViewModel $erro): string
    {
        return $erro?->mensagem ?? 'Ocorreu um erro inesperado ao processar sua solicitação.';
    }
}
