<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use DateTimeImmutable;
use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Components\ConversaAreaComponent;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Errors\ErrorViewModel;
use PsycheAI\Presentation\Web\Errors\ErrorViewModelFactory;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;
use PsycheAI\Presentation\Web\ViewModels\MensagemViewModel;

/**
 * Tela de Conversa. Desde a Sprint 12, inicia uma Sessao automaticamente
 * e mantém seu id em $_SESSION nativa do PHP para persistir entre
 * requisições da mesma aba/navegador; envia mensagens através do
 * endpoint POST /sessions/{id}/messages e reconstrói o histórico
 * filtrando GET /events por sessaoId, reaproveitando um endpoint já
 * existente em vez de criar mais um só para leitura.
 *
 * Sprint 17 (Interface Conversacional) acrescenta duas coisas, ainda sem
 * autenticação real (isso é a Sprint 18):
 *
 * 1. Identidade por cookie: em vez do Sujeito "visitante" fixo
 *    compartilhado por todo mundo, cada navegador recebe um cookie de
 *    longa duração (CHAVE_PESSOA_ID) com um id pseudônimo estável. Isso
 *    isola o discurso de cada pessoa que usa a conversa — necessário
 *    para que os motores Freud/Lacan (Sprints 15-16) observem
 *    recorrências de UM sujeito, não a mistura de todos os visitantes.
 *    Continua sem login: é só uma identidade anônima estável por
 *    navegador, que a Sprint 18 poderá ligar a uma conta real sem
 *    perder o histórico já acumulado.
 * 2. Atualização incremental via fetch(): a rota JSON
 *    POST /conversa/mensagens (método mensagens()) devolve só o
 *    fragmento HTML da área de conversa (ConversaAreaComponent), que o
 *    JS da view troca no DOM sem recarregar a página inteira. A rota
 *    HTML POST /conversa/enviar (método enviar()) continua existindo
 *    tal como antes — é o fallback funcional quando JavaScript está
 *    desabilitado.
 *
 * Não estende AbstractResourceController/AbstractCrudResourceController:
 * o ceremonial de listagem/CRUD genérico não se aplica a uma tela de
 * conversa com fluxo próprio de iniciar/enviar.
 */
final class ConversaController
{
    private const SUJEITO_NOME_PADRAO = 'Visitante';
    private const CHAVE_SESSAO_ATIVA = 'psyche_conversa_sessao_id';
    private const CHAVE_PESSOA_ID = 'psyche_pessoa_id';
    private const DURACAO_COOKIE_PESSOA_EM_SEGUNDOS = 60 * 60 * 24 * 365;
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
        $resultado = $this->processarEnvio($request);

        if ($resultado['sessaoId'] === null) {
            return ErrorController::renderizar(
                ErrorViewModelFactory::comunicacao('sessions'),
                $this->viewRenderer,
                self::ROTA
            );
        }

        return $this->renderizarConversa(
            $resultado['sessaoId'],
            $resultado['alerta'],
            $resultado['tipoAlerta'],
            $resultado['valorConteudo']
        );
    }

    /**
     * Mesma lógica de enviar(), mas devolve o fragmento HTML da área de
     * conversa em JSON em vez de recarregar a página inteira — consumido
     * pelo fetch() de conversa/index.php (Sprint 17).
     */
    public function mensagens(Request $request): Response
    {
        $resultado = $this->processarEnvio($request);

        if ($resultado['sessaoId'] === null) {
            return Response::json(
                ['sucesso' => false, 'alerta' => ErrorViewModelFactory::comunicacao('sessions')->mensagem],
                502
            );
        }

        return $this->renderizarConversaJson(
            $resultado['sessaoId'],
            $resultado['alerta'],
            $resultado['tipoAlerta'],
            $resultado['valorConteudo']
        );
    }

    /**
     * @return array{sessaoId: ?string, alerta: ?string, tipoAlerta: string, valorConteudo: string}
     */
    private function processarEnvio(Request $request): array
    {
        $conteudo = trim((string) $request->input('conteudo', ''));

        $sessaoId = $this->sessaoAtivaOuNova();

        if ($sessaoId === null) {
            return ['sessaoId' => null, 'alerta' => null, 'tipoAlerta' => 'erro', 'valorConteudo' => $conteudo];
        }

        if ($conteudo === '') {
            return [
                'sessaoId' => $sessaoId,
                'alerta' => 'A mensagem não pode ser vazia.',
                'tipoAlerta' => 'erro',
                'valorConteudo' => $conteudo,
            ];
        }

        $resposta = $this->httpClient->post('sessions/' . $sessaoId . '/messages', ['conteudo' => $conteudo]);

        if ($resposta->sucesso) {
            return ['sessaoId' => $sessaoId, 'alerta' => null, 'tipoAlerta' => 'erro', 'valorConteudo' => ''];
        }

        if ($resposta->erro?->tipo === ErrorType::NAO_ENCONTRADO) {
            return $this->recuperarDeSessaoInexistente($conteudo);
        }

        return [
            'sessaoId' => $sessaoId,
            'alerta' => $this->mensagemErro($resposta->erro),
            'tipoAlerta' => 'erro',
            'valorConteudo' => $conteudo,
        ];
    }

    /**
     * @return array{sessaoId: ?string, alerta: ?string, tipoAlerta: string, valorConteudo: string}
     */
    private function recuperarDeSessaoInexistente(string $conteudo): array
    {
        $this->limparSessaoAtiva();
        $novaSessaoId = $this->criarNovaSessao();

        if ($novaSessaoId === null) {
            return ['sessaoId' => null, 'alerta' => null, 'tipoAlerta' => 'erro', 'valorConteudo' => $conteudo];
        }

        $reenvio = $this->httpClient->post('sessions/' . $novaSessaoId . '/messages', ['conteudo' => $conteudo]);

        if (!$reenvio->sucesso) {
            return [
                'sessaoId' => $novaSessaoId,
                'alerta' => $this->mensagemErro($reenvio->erro),
                'tipoAlerta' => 'erro',
                'valorConteudo' => $conteudo,
            ];
        }

        return [
            'sessaoId' => $novaSessaoId,
            'alerta' => 'Sua conversa anterior não estava mais disponível; uma nova conversa foi iniciada.',
            'tipoAlerta' => 'info',
            'valorConteudo' => '',
        ];
    }

    private function renderizarConversa(
        string $sessaoId,
        ?string $alerta = null,
        string $tipoAlerta = 'erro',
        string $valorConteudo = ''
    ): Response {
        [$areaConversaHtml, $alerta] = $this->montarAreaConversa($sessaoId, $alerta, $tipoAlerta);

        $html = $this->viewRenderer->renderComLayout(
            'conversa/index',
            [
                'areaConversaHtml' => $areaConversaHtml,
                'valorConteudo' => $valorConteudo,
            ],
            'Conversa',
            self::ROTA
        );

        return Response::ok($html);
    }

    private function renderizarConversaJson(
        string $sessaoId,
        ?string $alerta,
        string $tipoAlerta,
        string $valorConteudo
    ): Response {
        [$areaConversaHtml, $alertaFinal] = $this->montarAreaConversa($sessaoId, $alerta, $tipoAlerta);

        return Response::json([
            'sucesso' => $alertaFinal === null || $tipoAlerta !== 'erro',
            'html' => $areaConversaHtml,
            'valorConteudo' => $valorConteudo,
        ]);
    }

    /**
     * @return array{0: string, 1: ?string}
     */
    private function montarAreaConversa(string $sessaoId, ?string $alerta, string $tipoAlerta): array
    {
        $eventosResposta = $this->httpClient->get('events');

        if ($eventosResposta->sucesso) {
            $mensagens = MensagemViewModel::historicoDaSessao($eventosResposta->dados, $sessaoId);
        } else {
            $mensagens = [];
            $alerta ??= $this->mensagemErro($eventosResposta->erro);
        }

        return [ConversaAreaComponent::render($mensagens, $alerta, $tipoAlerta), $alerta];
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
        $pessoaId = $this->pessoaIdAtivaOuNova();

        if (!$this->garantirSujeito($pessoaId)) {
            return null;
        }

        $id = bin2hex(random_bytes(8));

        $resposta = $this->httpClient->post('sessions', [
            'id' => $id,
            'sujeitoId' => $pessoaId,
            'data' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
        ]);

        if (!$resposta->sucesso) {
            return null;
        }

        $_SESSION[self::CHAVE_SESSAO_ATIVA] = $id;

        return $id;
    }

    /**
     * Cookie de longa duração, independente de $_SESSION: identifica de
     * forma pseudônima e estável cada navegador, para que suas Sessões
     * fiquem sempre sob o mesmo Sujeito ao longo de visitas diferentes
     * (ver docblock da classe). Só é lido/gerado quando uma nova Sessao
     * precisa ser criada — reaproveitar uma Sessao já ativa em
     * $_SESSION não exige tocar no cookie.
     */
    private function pessoaIdAtivaOuNova(): string
    {
        $pessoaId = $_COOKIE[self::CHAVE_PESSOA_ID] ?? null;

        if (is_string($pessoaId) && $pessoaId !== '') {
            return $pessoaId;
        }

        $novoPessoaId = bin2hex(random_bytes(16));

        $_COOKIE[self::CHAVE_PESSOA_ID] = $novoPessoaId;

        if (!headers_sent()) {
            setcookie(self::CHAVE_PESSOA_ID, $novoPessoaId, [
                'expires' => time() + self::DURACAO_COOKIE_PESSOA_EM_SEGUNDOS,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }

        return $novoPessoaId;
    }

    private function garantirSujeito(string $sujeitoId): bool
    {
        $existente = $this->httpClient->get('subjects/' . $sujeitoId);

        if ($existente->sucesso) {
            return true;
        }

        $criado = $this->httpClient->post('subjects', [
            'id' => $sujeitoId,
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
