<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Support;

use PsycheAI\Presentation\Web\Client\ApiResponse;
use PsycheAI\Presentation\Web\Client\BinaryApiResponse;
use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Errors\ErrorViewModelFactory;

/**
 * Decora HttpClientStub para testar ConversaController: delega tudo ao
 * duplo genérico, exceto que a primeira chamada a POST
 * "sessions/{id}/messages" pode ser forçada a falhar com um ErrorType
 * escolhido — necessário porque HttpClientStub, sendo genérico para
 * todos os módulos, não simula a validação de Sessao existente que a
 * API real faz nesse endpoint novo da Sprint 12.
 */
final class MensagemHttpClientFake implements HttpClientInterface
{
    private HttpClientStub $delegado;

    /**
     * @param array<string, array<int, array<string, mixed>>> $recursos
     * @param ?string $textoTranscricao Sprint 32 (Interface de Voz da ECO):
     *        quando informado, simula a resposta de POST /audio/transcricao
     *        (`{"texto": ...}`) em vez de delegar a HttpClientStub — que não
     *        conhece esse recurso específico.
     */
    public function __construct(
        array $recursos = ['events' => []],
        private ?ErrorType $falhaNoProximoEnvioDeMensagem = null,
        private readonly ?string $textoTranscricao = null
    ) {
        $this->delegado = new HttpClientStub($recursos);
    }

    /**
     * @param array<string, string> $parametros
     */
    public function get(string $recurso, array $parametros = []): ApiResponse
    {
        return $this->delegado->get($recurso, $parametros);
    }

    /**
     * @param array<string, string> $dados
     */
    public function post(string $recurso, array $dados = []): ApiResponse
    {
        $ehEnvioDeMensagem = preg_match('#^sessions/[^/]+/messages$#', trim($recurso, '/')) === 1;

        if ($ehEnvioDeMensagem && $this->falhaNoProximoEnvioDeMensagem !== null) {
            $tipo = $this->falhaNoProximoEnvioDeMensagem;
            $this->falhaNoProximoEnvioDeMensagem = null;

            return ApiResponse::falha(match ($tipo) {
                ErrorType::NAO_ENCONTRADO => ErrorViewModelFactory::naoEncontrado($recurso),
                ErrorType::VALIDACAO => ErrorViewModelFactory::validacao(['conteudo' => 'Conteúdo inválido.']),
                ErrorType::COMUNICACAO => ErrorViewModelFactory::comunicacao($recurso),
                ErrorType::INTERNO => ErrorViewModelFactory::interno(),
            });
        }

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
        if (trim($recurso, '/') === 'audio/transcricao' && $this->textoTranscricao !== null) {
            return ApiResponse::sucesso(['texto' => $this->textoTranscricao]);
        }

        return $this->delegado->postBinario($recurso, $binario);
    }

    public function getBinario(string $recurso): BinaryApiResponse
    {
        return $this->delegado->getBinario($recurso);
    }
}
