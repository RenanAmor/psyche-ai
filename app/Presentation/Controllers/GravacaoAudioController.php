<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Controllers;

use PsycheAI\Application\Services\GravacaoAudioApplicationService;
use PsycheAI\Presentation\Http\HttpException;
use PsycheAI\Presentation\Http\Request;
use PsycheAI\Presentation\Http\Response;
use PsycheAI\Presentation\Responses\GravacaoAudioResponse;

/**
 * Expõe a captura de áudio da sessão (Sprint 22). Primeiro Controller do
 * projeto a lidar com corpo binário: enviar() lê o áudio bruto do corpo
 * da requisição (Request::corpoBinario(), nunca JSON) e baixar() devolve
 * os mesmos bytes originais — nunca o texto transcrito — para que o
 * analista possa ouvir a gravação e validar o que o pipeline escreveu.
 */
final class GravacaoAudioController extends Controller
{
    private const CONTENT_TYPE_AUDIO = 'audio/webm';

    public function __construct(
        private readonly GravacaoAudioApplicationService $service
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function enviar(Request $request, array $params): Response
    {
        $audioBinario = $request->corpoBinario();

        if ($audioBinario === '') {
            throw HttpException::badRequest('O corpo da requisição deve conter o áudio gravado.');
        }

        $dto = $this->service->registrar($params['id'], $audioBinario);

        return $this->criado(GravacaoAudioResponse::fromDTO($dto)->toArray());
    }

    /**
     * Transcreve um único turno de fala e devolve só o texto, em vez de
     * persistir uma GravacaoAudio (Sprint 32, Interface de Voz da ECO) —
     * sem parâmetro de sessão porque não grava nada. Ver o docblock de
     * GravacaoAudioApplicationService::transcreverTexto() para o porquê de
     * não reaproveitar enviar()+RegistrarGravacaoAudio aqui.
     *
     * @param array<string, string> $params
     */
    public function transcrever(Request $request, array $params): Response
    {
        $audioBinario = $request->corpoBinario();

        if ($audioBinario === '') {
            throw HttpException::badRequest('O corpo da requisição deve conter o áudio gravado.');
        }

        $texto = $this->service->transcreverTexto($audioBinario);

        return $this->sucesso(['texto' => $texto]);
    }

    /**
     * @param array<string, string> $params
     */
    public function baixar(Request $request, array $params): Response
    {
        $gravacao = $this->service->buscarPorSessao($params['id']);

        if ($gravacao === null) {
            throw HttpException::naoEncontrado(
                sprintf('Nenhuma gravação de áudio encontrada para a sessão "%s".', $params['id'])
            );
        }

        $bytes = $this->service->bytesDoArquivo($gravacao->id);

        return new Response(200, $bytes, ['Content-Type' => self::CONTENT_TYPE_AUDIO]);
    }
}
