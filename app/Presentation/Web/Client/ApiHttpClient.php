<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Client;

use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Errors\ErrorViewModel;
use PsycheAI\Presentation\Web\Errors\ErrorViewModelFactory;

/**
 * Implementação de produção de HttpClientInterface: fala HTTP de verdade
 * (via cURL) com a API REST da Sprint 10. Traduz o envelope JSON
 * padronizado ({"success": bool, "data"|"message"/"errors": ...}) e os
 * status HTTP reais devolvidos pela API para o mesmo catálogo fechado de
 * ErrorType já usado pela interface — nenhum Controller precisa conhecer
 * status HTTP cru ou o formato do envelope.
 */
final class ApiHttpClient implements HttpClientInterface
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly int $timeoutSegundos = 5
    ) {
    }

    /**
     * @param array<string, string> $parametros
     */
    public function get(string $recurso, array $parametros = []): ApiResponse
    {
        return $this->requisitar('GET', $recurso, $parametros);
    }

    /**
     * @param array<string, string> $dados
     */
    public function post(string $recurso, array $dados = []): ApiResponse
    {
        return $this->requisitar('POST', $recurso, [], $dados);
    }

    /**
     * @param array<string, string> $dados
     */
    public function put(string $recurso, array $dados = []): ApiResponse
    {
        return $this->requisitar('PUT', $recurso, [], $dados);
    }

    public function delete(string $recurso): ApiResponse
    {
        return $this->requisitar('DELETE', $recurso);
    }

    public function postBinario(string $recurso, string $binario): ApiResponse
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($recurso, '/');

        $handle = curl_init($url);
        curl_setopt_array($handle, [
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->timeoutSegundos,
            CURLOPT_TIMEOUT => $this->timeoutSegundos,
            CURLOPT_HTTPHEADER => ['Content-Type: application/octet-stream', 'Accept: application/json'],
            CURLOPT_POSTFIELDS => $binario,
        ]);

        $corpoResposta = curl_exec($handle);
        $codigoErroCurl = curl_errno($handle);
        $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        curl_close($handle);

        if ($codigoErroCurl !== 0 || $corpoResposta === false) {
            return ApiResponse::falha(ErrorViewModelFactory::comunicacao($recurso));
        }

        return $this->interpretar($status, (string) $corpoResposta, $recurso);
    }

    public function getBinario(string $recurso): BinaryApiResponse
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($recurso, '/');

        $handle = curl_init($url);
        curl_setopt_array($handle, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->timeoutSegundos,
            CURLOPT_TIMEOUT => $this->timeoutSegundos,
        ]);

        $corpoResposta = curl_exec($handle);
        $codigoErroCurl = curl_errno($handle);
        $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        $contentType = curl_getinfo($handle, CURLINFO_CONTENT_TYPE);
        curl_close($handle);

        if ($codigoErroCurl !== 0 || $corpoResposta === false) {
            return BinaryApiResponse::falha(ErrorViewModelFactory::comunicacao($recurso));
        }

        if ($status >= 200 && $status < 300) {
            return BinaryApiResponse::sucesso((string) $corpoResposta, is_string($contentType) ? $contentType : null);
        }

        return BinaryApiResponse::falha($this->interpretar($status, (string) $corpoResposta, $recurso)->erro ?? ErrorViewModelFactory::interno());
    }

    /**
     * @param array<string, string> $parametros
     * @param array<string, string>|null $corpo
     */
    private function requisitar(string $metodo, string $recurso, array $parametros = [], ?array $corpo = null): ApiResponse
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($recurso, '/');

        if ($parametros !== []) {
            $url .= '?' . http_build_query($parametros);
        }

        $handle = curl_init($url);

        $opcoes = [
            CURLOPT_CUSTOMREQUEST => $metodo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->timeoutSegundos,
            CURLOPT_TIMEOUT => $this->timeoutSegundos,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Accept: application/json'],
        ];

        if ($corpo !== null) {
            $opcoes[CURLOPT_POSTFIELDS] = (string) json_encode($corpo, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        curl_setopt_array($handle, $opcoes);

        $corpoResposta = curl_exec($handle);
        $codigoErroCurl = curl_errno($handle);
        $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);

        // CURLE_OPERATION_TIMEDOUT (28) cobre tanto "não conseguiu conectar
        // dentro do prazo" quanto "conectou, mas a resposta demorou demais"
        // — só o segundo caso é um timeout de verdade. CONNECT_TIME > 0
        // confirma que o handshake TCP aconteceu antes do estouro do prazo.
        if ($codigoErroCurl === CURLE_OPERATION_TIMEDOUT) {
            $conectou = (float) curl_getinfo($handle, CURLINFO_CONNECT_TIME) > 0.0;

            return ApiResponse::falha(
                $conectou ? ErrorViewModelFactory::timeout($recurso) : ErrorViewModelFactory::comunicacao($recurso)
            );
        }

        if ($codigoErroCurl !== 0 || $corpoResposta === false) {
            return ApiResponse::falha(ErrorViewModelFactory::comunicacao($recurso));
        }

        return $this->interpretar($status, (string) $corpoResposta, $recurso);
    }

    private function interpretar(int $status, string $corpoResposta, string $recurso): ApiResponse
    {
        if ($status === 204) {
            return ApiResponse::sucesso([]);
        }

        $decodificado = json_decode($corpoResposta, true);

        if (!is_array($decodificado)) {
            return ApiResponse::falha(ErrorViewModelFactory::interno());
        }

        if ($status >= 200 && $status < 300 && ($decodificado['success'] ?? false) === true) {
            /** @var array<int, array<string, mixed>>|array<string, mixed> $dados */
            $dados = $decodificado['data'] ?? [];

            return ApiResponse::sucesso($dados);
        }

        $mensagem = is_string($decodificado['message'] ?? null) ? $decodificado['message'] : '';

        return ApiResponse::falha($this->erroParaStatus($status, $recurso, $mensagem));
    }

    private function erroParaStatus(int $status, string $recurso, string $mensagemApi): ErrorViewModel
    {
        return match (true) {
            $status === 404 => new ErrorViewModel(
                ErrorType::NAO_ENCONTRADO,
                'Recurso não encontrado',
                $mensagemApi !== '' ? $mensagemApi : sprintf('O recurso "%s" solicitado não existe ou foi removido.', $recurso)
            ),
            in_array($status, [400, 401, 409, 422], true) => new ErrorViewModel(
                ErrorType::VALIDACAO,
                'Dados inválidos',
                $mensagemApi !== '' ? $mensagemApi : 'Corrija os campos indicados antes de continuar.'
            ),
            default => new ErrorViewModel(
                ErrorType::INTERNO,
                'Erro interno',
                'Ocorreu um erro inesperado ao processar sua solicitação.'
            ),
        };
    }
}
