<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Support;

use PsycheAI\Presentation\Web\Client\ApiResponse;
use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Errors\ErrorViewModel;
use PsycheAI\Presentation\Web\Errors\ErrorViewModelFactory;

/**
 * Decora HttpClientStub para testar HistoricoSujeitoController: delega
 * "subjects/{id}" (busca do Sujeito) ao duplo genérico, mas responde
 * "subjects/{id}/timeline" e "subjects/{id}/consolidation" com valores
 * fixos configuráveis — HttpClientStub, sendo genérico e baseado em
 * "recurso/id", não modela recursos aninhados de três segmentos como
 * estes dois endpoints novos da Sprint 13.
 */
final class HistoricoHttpClientFake implements HttpClientInterface
{
    private HttpClientStub $delegado;

    /**
     * Últimos parâmetros de query recebidos em GET .../timeline —
     * exposto apenas para o teste confirmar que o Controller repassa os
     * filtros da requisição sem alterá-los.
     *
     * @var array<string, string>|null
     */
    public ?array $ultimosParametrosDeTimeline = null;

    /**
     * @param array<string, array<int, array<string, mixed>>> $recursos
     * @param array<int, array<string, mixed>> $itensDaLinhaDoTempo
     * @param array<string, mixed> $consolidacao
     */
    public function __construct(
        array $recursos = [],
        private readonly array $itensDaLinhaDoTempo = [],
        private readonly array $consolidacao = [],
        private readonly ?ErrorType $falhaNaLinhaDoTempo = null,
        private readonly ?ErrorType $falhaNaConsolidacao = null,
        private readonly int $totalDePaginas = 1
    ) {
        $this->delegado = new HttpClientStub($recursos);
    }

    /**
     * @param array<string, string> $parametros
     */
    public function get(string $recurso, array $parametros = []): ApiResponse
    {
        $caminho = trim($recurso, '/');

        if (preg_match('#^subjects/[^/]+/timeline$#', $caminho) === 1) {
            $this->ultimosParametrosDeTimeline = $parametros;

            if ($this->falhaNaLinhaDoTempo !== null) {
                return ApiResponse::falha($this->erroPara($this->falhaNaLinhaDoTempo, 'timeline'));
            }

            return ApiResponse::sucesso([
                'itens' => $this->itensDaLinhaDoTempo,
                'pagina' => (int) ($parametros['pagina'] ?? 1),
                'porPagina' => 20,
                'total' => count($this->itensDaLinhaDoTempo),
                'totalDePaginas' => $this->totalDePaginas,
            ]);
        }

        if (preg_match('#^subjects/[^/]+/consolidation$#', $caminho) === 1) {
            if ($this->falhaNaConsolidacao !== null) {
                return ApiResponse::falha($this->erroPara($this->falhaNaConsolidacao, 'consolidation'));
            }

            return ApiResponse::sucesso($this->consolidacao);
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
