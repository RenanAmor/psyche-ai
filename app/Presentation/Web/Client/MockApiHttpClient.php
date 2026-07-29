<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Client;

use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Errors\ErrorViewModel;
use PsycheAI\Presentation\Web\Errors\ErrorViewModelFactory;

/**
 * Implementação mockada de HttpClientInterface. Não realiza nenhuma
 * chamada real de rede, nem acessa API, Application ou Domain — apenas
 * devolve dados simulados fixos, na mesma forma (arrays associativos)
 * que a futura API REST devolverá em seu JSON.
 *
 * Também permite simular os quatro tipos de erro da interface,
 * construída via comFalha(), para que páginas e testes possam exercitar
 * os estados de erro sem depender de uma falha real.
 */
final class MockApiHttpClient implements HttpClientInterface
{
    private const RECURSOS_PADRAO = [
        'sujeitos' => [
            ['id' => 'sub-001', 'nome' => 'Sujeito A', 'quantidadeDeSessoes' => 3],
            ['id' => 'sub-002', 'nome' => 'Sujeito B', 'quantidadeDeSessoes' => 1],
            ['id' => 'sub-003', 'nome' => 'Sujeito C', 'quantidadeDeSessoes' => 0],
        ],
        'sessoes' => [
            ['id' => 'ses-001', 'data' => '2026-06-02', 'quantidadeDeDiscursos' => 2],
            ['id' => 'ses-002', 'data' => '2026-06-16', 'quantidadeDeDiscursos' => 1],
        ],
        'discursos' => [
            ['id' => 'dsc-001', 'conteudo' => 'Relato inicial da sessão.', 'quantidadeDeEventos' => 4],
            ['id' => 'dsc-002', 'conteudo' => 'Retomada de tema anterior.', 'quantidadeDeEventos' => 2],
        ],
        'memorias' => [
            ['id' => 'mem-001', 'quantidadeDeSessoes' => 3],
        ],
        'eventos-discursivos' => [],
    ];

    /**
     * @param array<string, array<int, array<string, mixed>>> $recursos
     */
    public function __construct(
        private readonly array $recursos = self::RECURSOS_PADRAO,
        private readonly ?ErrorType $falhaForcada = null
    ) {
    }

    public static function comFalha(ErrorType $tipo): self
    {
        return new self(falhaForcada: $tipo);
    }

    public function get(string $recurso, array $parametros = []): ApiResponse
    {
        $chave = trim($recurso, '/');

        if ($this->falhaForcada !== null) {
            return ApiResponse::falha($this->erroPara($this->falhaForcada, $chave));
        }

        if (!array_key_exists($chave, $this->recursos)) {
            return ApiResponse::falha(ErrorViewModelFactory::naoEncontrado($chave));
        }

        return ApiResponse::sucesso($this->recursos[$chave]);
    }

    public function post(string $recurso, array $dados = []): ApiResponse
    {
        $chave = trim($recurso, '/');

        if ($this->falhaForcada !== null) {
            return ApiResponse::falha($this->erroPara($this->falhaForcada, $chave));
        }

        return ApiResponse::sucesso($dados);
    }

    private function erroPara(ErrorType $tipo, string $recurso): ErrorViewModel
    {
        return match ($tipo) {
            ErrorType::COMUNICACAO => ErrorViewModelFactory::comunicacao($recurso),
            ErrorType::NAO_ENCONTRADO => ErrorViewModelFactory::naoEncontrado($recurso),
            ErrorType::INTERNO => ErrorViewModelFactory::interno(),
            ErrorType::VALIDACAO => ErrorViewModelFactory::validacao(['recurso' => 'Requisição inválida.']),
        };
    }
}
