<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Controllers;

use DateTimeImmutable;
use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Components\ConversaAreaComponent;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Errors\ErrorViewModel;
use PsycheAI\Presentation\Web\Errors\ErrorViewModelFactory;
use PsycheAI\Presentation\Web\Http\BasePath;
use PsycheAI\Presentation\Web\Http\Request;
use PsycheAI\Presentation\Web\Http\Response;
use PsycheAI\Presentation\Web\Http\ViewRenderer;
use PsycheAI\Presentation\Web\Security\PortaoDeParticipante;
use PsycheAI\Presentation\Web\ViewModels\MensagemViewModel;

/**
 * Tela de Conversa. Desde a Sprint 12, inicia uma Sessao automaticamente
 * e mantém seu id em $_SESSION nativa do PHP para persistir entre
 * requisições da mesma aba/navegador; envia mensagens através do
 * endpoint POST /sessions/{id}/messages e reconstrói o histórico
 * filtrando GET /events por sessaoId, reaproveitando um endpoint já
 * existente em vez de criar mais um só para leitura.
 *
 * A identidade do Sujeito é o id do Participante autenticado por
 * `PortaoDeParticipante` (`/conversa*` é gateado por ele em `Routes.php`) —
 * o mesmo id é usado como id do Sujeito (`garantirSujeito()`), preservando
 * o histórico entre visitas sem depender de cookie algum. Isso substitui
 * duas gerações anteriores: o cookie pseudônimo de longa duração da Sprint
 * 17 e a conta real opcional (self-signup) da Sprint 20 — ambas retiradas
 * em favor de contas de Participante provisionadas pelo Analista, sem
 * autocadastro público.
 *
 * Atualização incremental via fetch(): a rota JSON POST /conversa/mensagens
 * (método mensagens()) devolve só o fragmento HTML da área de conversa
 * (ConversaAreaComponent), que o JS da view troca no DOM sem recarregar a
 * página inteira. A rota HTML POST /conversa/enviar (método enviar())
 * continua existindo tal como antes — é o fallback funcional quando
 * JavaScript está desabilitado.
 *
 * Não estende AbstractResourceController/AbstractCrudResourceController:
 * o ceremonial de listagem/CRUD genérico não se aplica a uma tela de
 * conversa com fluxo próprio de iniciar/enviar.
 */
final class ConversaController
{
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
     * Recebe um único turno de fala da Interface de Voz da ECO (Sprint 32)
     * e o transcreve de forma síncrona via POST /audio/transcricao, para
     * então seguir pelo mesmo caminho de processarConteudo()/enviar() que
     * uma mensagem digitada já percorre — mesmo pipeline, mesma geração de
     * pergunta socrática, mesmo áudio de resposta, sem tocar os Motores.
     * Ausência de fala reconhecida não cria turno nenhum (mesmo espírito do
     * silêncio em Fluxo-Conversacional.md).
     */
    public function mensagensAudio(Request $request): Response
    {
        $audioBinario = $request->corpoBinario();

        if ($audioBinario === '') {
            return Response::json(['sucesso' => false, 'alerta' => 'A gravação está vazia.'], 400);
        }

        $sessaoId = $this->sessaoAtivaOuNova();

        if ($sessaoId === null) {
            return Response::json(
                ['sucesso' => false, 'alerta' => ErrorViewModelFactory::comunicacao('sessions')->mensagem],
                502
            );
        }

        $transcricao = $this->httpClient->postBinario('audio/transcricao', $audioBinario);

        if (!$transcricao->sucesso) {
            return Response::json(['sucesso' => false, 'alerta' => $this->mensagemErro($transcricao->erro)], 502);
        }

        $textoTranscrito = trim((string) ($transcricao->dados['texto'] ?? ''));

        if ($textoTranscrito === '') {
            return $this->renderizarConversaJson($sessaoId, 'Não conseguimos identificar fala nessa gravação.', 'erro', '', '');
        }

        $resultado = $this->processarConteudo($textoTranscrito);

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
            $resultado['valorConteudo'],
            $textoTranscrito
        );
    }

    /**
     * Recebe a gravação contínua de áudio da sessão (Sprint 22, "Captura
     * de Áudio da Sessão") e repassa o binário bruto para
     * POST /sessions/{id}/audio — a transcrição em EventoDiscursivo
     * acontece depois, de forma assíncrona (bin/transcrever-gravacoes.php),
     * nunca aqui. Mesma identidade por Participante autenticado/Sessao
     * ativa do restante de /conversa*, sem tocar os Motores Freud/Lacan
     * (Regra 11).
     */
    public function audio(Request $request): Response
    {
        $audioBinario = $request->corpoBinario();

        if ($audioBinario === '') {
            return Response::json(['sucesso' => false, 'alerta' => 'A gravação está vazia.'], 400);
        }

        $sessaoId = $this->sessaoAtivaOuNova();

        if ($sessaoId === null) {
            return Response::json(
                ['sucesso' => false, 'alerta' => ErrorViewModelFactory::comunicacao('sessions')->mensagem],
                502
            );
        }

        $resposta = $this->httpClient->postBinario('sessions/' . $sessaoId . '/audio', $audioBinario);

        if (!$resposta->sucesso) {
            return Response::json(['sucesso' => false, 'alerta' => $this->mensagemErro($resposta->erro)], 502);
        }

        return Response::json(['sucesso' => true]);
    }

    /**
     * Sintetiza em voz a resposta do sistema (Sprint 24, Voz de Saída) —
     * repassa o áudio devolvido por GET /events/{id}/audio, mesma
     * disciplina de SessoesController::audio() (getBinario(), nunca fala
     * com Infrastructure direto).
     */
    public function audioResposta(Request $request): Response
    {
        $id = (string) $request->routeParam('id', '');
        $resposta = $this->httpClient->getBinario('events/' . $id . '/audio');

        if (!$resposta->sucesso) {
            return Response::naoEncontrado('Não foi possível gerar o áudio desta mensagem.');
        }

        return new Response($resposta->bytes, 200, ['Content-Type' => $resposta->contentType ?? 'audio/mpeg']);
    }

    /**
     * @return array{sessaoId: ?string, alerta: ?string, tipoAlerta: string, valorConteudo: string}
     */
    private function processarEnvio(Request $request): array
    {
        return $this->processarConteudo(trim((string) $request->input('conteudo', '')));
    }

    /**
     * Núcleo de processarEnvio(), extraído na Sprint 32 para que
     * mensagensAudio() reaproveite exatamente o mesmo caminho de uma
     * mensagem digitada (validação, envio a POST /sessions/{id}/messages,
     * recuperação de sessão inexistente) a partir de um texto já
     * transcrito, em vez de um $request->input().
     *
     * @return array{sessaoId: ?string, alerta: ?string, tipoAlerta: string, valorConteudo: string}
     */
    private function processarConteudo(string $conteudo): array
    {
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
            self::ROTA,
            'layout-eco'
        );

        return Response::ok($html);
    }

    /**
     * @param ?string $textoTranscrito Presente (mesmo vazio) só quando a
     *        chamada vem de mensagensAudio() (Sprint 32) — null preserva a
     *        chave 'textoTranscrito' fora do JSON devolvido a mensagens(),
     *        que nenhum teste/consumidor existente espera.
     */
    private function renderizarConversaJson(
        string $sessaoId,
        ?string $alerta,
        string $tipoAlerta,
        string $valorConteudo,
        ?string $textoTranscrito = null
    ): Response {
        [$areaConversaHtml, $alertaFinal, $mensagens] = $this->montarAreaConversa($sessaoId, $alerta, $tipoAlerta);

        $corpo = [
            'sucesso' => $alertaFinal === null || $tipoAlerta !== 'erro',
            'html' => $areaConversaHtml,
            'valorConteudo' => $valorConteudo,
            'audioRespostaUrl' => $this->ultimaRespostaAudioUrl($mensagens),
        ];

        if ($textoTranscrito !== null) {
            $corpo['textoTranscrito'] = $textoTranscrito;
        }

        return Response::json($corpo);
    }

    /**
     * @param MensagemViewModel[] $mensagens
     */
    private function ultimaRespostaAudioUrl(array $mensagens): ?string
    {
        $mensagensDoSistema = array_values(array_filter(
            $mensagens,
            static fn (MensagemViewModel $mensagem): bool => $mensagem->autor === 'Sistema'
        ));

        $ultima = $mensagensDoSistema[count($mensagensDoSistema) - 1] ?? null;

        return $ultima === null ? null : BasePath::url('/conversa/mensagens/' . $ultima->id . '/audio');
    }

    /**
     * @return array{0: string, 1: ?string, 2: MensagemViewModel[]}
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

        return [ConversaAreaComponent::render($mensagens, $alerta, $tipoAlerta), $alerta, $mensagens];
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
        $participanteId = PortaoDeParticipante::participanteId();

        if ($participanteId === null) {
            // Defensivo: /conversa* é protegida por PortaoDeParticipante::proteger()
            // em Routes.php, então uma requisição autenticada sempre chega aqui
            // com um id de participante — isso nunca deveria disparar em produção.
            return null;
        }

        if (!$this->garantirSujeito($participanteId)) {
            return null;
        }

        $id = bin2hex(random_bytes(8));

        $resposta = $this->httpClient->post('sessions', [
            'id' => $id,
            'sujeitoId' => $participanteId,
            'data' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
        ]);

        if (!$resposta->sucesso) {
            return null;
        }

        $_SESSION[self::CHAVE_SESSAO_ATIVA] = $id;

        return $id;
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
