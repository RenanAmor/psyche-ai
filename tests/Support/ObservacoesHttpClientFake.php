<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Support;

use PsycheAI\Presentation\Web\Client\ApiResponse;
use PsycheAI\Presentation\Web\Client\BinaryApiResponse;
use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Errors\ErrorViewModel;
use PsycheAI\Presentation\Web\Errors\ErrorViewModelFactory;

/**
 * Decora HttpClientStub para testar ObservacoesSujeitoController: delega
 * "subjects/{id}" (busca do Sujeito) ao duplo genérico, mas responde
 * "subjects/{id}/observations" e, desde a revisão pós-Sprint 16,
 * "subjects/{id}/observations/circuito" com valores fixos configuráveis —
 * HttpClientStub, sendo genérico e baseado em "recurso/id", não modela
 * recursos aninhados de três ou quatro segmentos como estes endpoints.
 */
final class ObservacoesHttpClientFake implements HttpClientInterface
{
    private HttpClientStub $delegado;

    /**
     * @param array<string, array<int, array<string, mixed>>> $recursos
     * @param array<int, array<string, mixed>> $recorrencias
     * @param array<int, array<string, mixed>> $observacoes
     * @param array<int, array<string, mixed>> $circuitos
     */
    public function __construct(
        array $recursos = [],
        private readonly array $recorrencias = [],
        private readonly array $observacoes = [],
        private readonly ?ErrorType $falhaNasObservacoes = null,
        private readonly array $circuitos = [],
        private readonly ?ErrorType $falhaNoCircuito = null
    ) {
        $this->delegado = new HttpClientStub($recursos);
    }

    /**
     * @param array<string, string> $parametros
     */
    public function get(string $recurso, array $parametros = []): ApiResponse
    {
        $caminho = trim($recurso, '/');

        if (preg_match('#^subjects/[^/]+/observations/circuito$#', $caminho) === 1) {
            if ($this->falhaNoCircuito !== null) {
                return ApiResponse::falha($this->erroPara($this->falhaNoCircuito, 'circuito'));
            }

            return ApiResponse::sucesso([
                'sujeitoId' => explode('/', $caminho)[1],
                'circuitos' => $this->circuitos,
            ]);
        }

        if (preg_match('#^subjects/[^/]+/observations$#', $caminho) === 1) {
            if ($this->falhaNasObservacoes !== null) {
                return ApiResponse::falha($this->erroPara($this->falhaNasObservacoes, 'observations'));
            }

            return ApiResponse::sucesso([
                'sujeitoId' => explode('/', $caminho)[1],
                'recorrencias' => $this->recorrencias,
                'observacoes' => $this->observacoes,
            ]);
        }

        return $this->delegado->get($recurso, $parametros);
    }

    /**
     * @param array<string, string> $dados
     */
    public function post(string $recurso, array $dados = []): ApiResponse
    {
        return $this->delegado->post($recurso, $dados);
    }

    /**
     * @param array<string, string> $dados
     */
    public function put(string $recurso, array $dados = []): ApiResponse
    {
        return $this->delegado->put($recurso, $dados);
    }

    public function delete(string $recurso): ApiResponse
    {
        return $this->delegado->delete($recurso);
    }

    public function postBinario(string $recurso, string $binario): ApiResponse
    {
        return $this->delegado->postBinario($recurso, $binario);
    }

    public function getBinario(string $recurso): BinaryApiResponse
    {
        return $this->delegado->getBinario($recurso);
    }

    private function erroPara(ErrorType $tipo, string $recurso): ErrorViewModel
    {
        return match ($tipo) {
            ErrorType::COMUNICACAO => ErrorViewModelFactory::comunicacao($recurso),
            ErrorType::NAO_ENCONTRADO => ErrorViewModelFactory::naoEncontrado($recurso),
            ErrorType::VALIDACAO => ErrorViewModelFactory::validacao(['recurso' => 'Requisição inválida.']),
            ErrorType::INTERNO => ErrorViewModelFactory::interno(),
            ErrorType::TIMEOUT => ErrorViewModelFactory::timeout($recurso),
        };
    }
}
