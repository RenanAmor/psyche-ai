<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Support;

use PsycheAI\Presentation\Web\Client\ApiResponse;
use PsycheAI\Presentation\Web\Client\HttpClientInterface;
use PsycheAI\Presentation\Web\Errors\ErrorType;
use PsycheAI\Presentation\Web\Errors\ErrorViewModel;
use PsycheAI\Presentation\Web\Errors\ErrorViewModelFactory;

/**
 * Duplo de teste de HttpClientInterface: mantém um armazenamento em
 * memória por recurso (chaves iguais aos paths reais da API — subjects,
 * sessions, discourses, memories, events) para exercitar rapidamente
 * Controllers e Rotas da interface web sem rede real. A verificação de
 * ponta a ponta com a API de verdade fica a cargo dos testes de
 * integração em tests/Integration/Web, que sobem a API real via HTTP.
 */
final class HttpClientStub implements HttpClientInterface
{
    /**
     * Credenciais simuladas do endpoint `POST /auth/login` (Sprint 18) —
     * usadas por qualquer teste de Web que precise passar pelo fluxo real
     * de login sem subir a API de verdade.
     */
    public const ANALISTA_ID_PADRAO = 'analista-teste';
    public const ANALISTA_EMAIL_PADRAO = 'analista@psyche.ai';
    public const ANALISTA_SENHA_PADRAO = 'senha-de-teste';

    /**
     * Idem, para `POST /auth/subject/login` (Sprint 20) — a conta do
     * Sujeito, distinta da conta do analista acima.
     */
    public const SUJEITO_ID_PADRAO = 'sujeito-teste';
    public const SUJEITO_EMAIL_PADRAO = 'sujeito@psyche.ai';
    public const SUJEITO_SENHA_PADRAO = 'senha-do-sujeito';

    private const RECURSOS_PADRAO = [
        'subjects' => [
            ['id' => 'sub-001', 'nome' => 'Sujeito A', 'quantidadeDeSessoes' => 3],
            ['id' => 'sub-002', 'nome' => 'Sujeito B', 'quantidadeDeSessoes' => 1],
            ['id' => 'sub-003', 'nome' => 'Sujeito C', 'quantidadeDeSessoes' => 0],
        ],
        'sessions' => [
            ['id' => 'ses-001', 'data' => '2026-06-02', 'quantidadeDeDiscursos' => 2],
            ['id' => 'ses-002', 'data' => '2026-06-16', 'quantidadeDeDiscursos' => 1],
        ],
        'discourses' => [
            ['id' => 'dsc-001', 'conteudo' => 'Relato inicial da sessão.', 'quantidadeDeEventos' => 4],
            ['id' => 'dsc-002', 'conteudo' => 'Retomada de tema anterior.', 'quantidadeDeEventos' => 2],
        ],
        'memories' => [
            ['id' => 'mem-001', 'quantidadeDeSessoes' => 3],
        ],
        'events' => [],
    ];

    /**
     * @var array<string, array<int, array<string, mixed>>>
     */
    private array $armazenamento;

    /**
     * @param array<string, array<int, array<string, mixed>>> $recursos
     */
    public function __construct(
        array $recursos = self::RECURSOS_PADRAO,
        private readonly ?ErrorType $falhaForcada = null
    ) {
        $this->armazenamento = $recursos;
    }

    public static function comFalha(ErrorType $tipo): self
    {
        return new self(falhaForcada: $tipo);
    }

    /**
     * @param array<string, string> $parametros
     */
    public function get(string $recurso, array $parametros = []): ApiResponse
    {
        [$chave, $id] = self::partir($recurso);

        if ($this->falhaForcada !== null) {
            return ApiResponse::falha($this->erroPara($this->falhaForcada, $chave));
        }

        if (!array_key_exists($chave, $this->armazenamento)) {
            return ApiResponse::falha(ErrorViewModelFactory::naoEncontrado($chave));
        }

        if ($id === null) {
            return ApiResponse::sucesso($this->armazenamento[$chave]);
        }

        $item = $this->localizar($chave, $id);

        return $item === null
            ? ApiResponse::falha(ErrorViewModelFactory::naoEncontrado($chave))
            : ApiResponse::sucesso($item);
    }

    /**
     * @param array<string, string> $dados
     */
    public function post(string $recurso, array $dados = []): ApiResponse
    {
        $chave = trim($recurso, '/');

        if ($this->falhaForcada !== null) {
            return ApiResponse::falha($this->erroPara($this->falhaForcada, $chave));
        }

        if ($chave === 'auth/login') {
            return $this->autenticar($dados);
        }

        if ($chave === 'auth/subject/login') {
            return $this->autenticarSujeito($dados);
        }

        if (preg_match('#^subjects/([^/]+)/account$#', $chave, $referencias) === 1) {
            return $this->registrarContaSujeito($referencias[1], $dados);
        }

        $this->armazenamento[$chave][] = $dados;

        return ApiResponse::sucesso($dados);
    }

    /**
     * @param array<string, string> $dados
     */
    private function autenticar(array $dados): ApiResponse
    {
        $credenciaisValidas = ($dados['email'] ?? null) === self::ANALISTA_EMAIL_PADRAO
            && ($dados['senha'] ?? null) === self::ANALISTA_SENHA_PADRAO;

        if (!$credenciaisValidas) {
            return ApiResponse::falha(ErrorViewModelFactory::validacao(['credenciais' => 'E-mail ou senha inválidos.']));
        }

        return ApiResponse::sucesso(['id' => self::ANALISTA_ID_PADRAO, 'email' => self::ANALISTA_EMAIL_PADRAO]);
    }

    /**
     * @param array<string, string> $dados
     */
    private function autenticarSujeito(array $dados): ApiResponse
    {
        $credenciaisValidas = ($dados['email'] ?? null) === self::SUJEITO_EMAIL_PADRAO
            && ($dados['senha'] ?? null) === self::SUJEITO_SENHA_PADRAO;

        if (!$credenciaisValidas) {
            return ApiResponse::falha(ErrorViewModelFactory::validacao(['credenciais' => 'E-mail ou senha inválidos.']));
        }

        return ApiResponse::sucesso(['id' => self::SUJEITO_ID_PADRAO, 'email' => self::SUJEITO_EMAIL_PADRAO]);
    }

    /**
     * @param array<string, string> $dados
     */
    private function registrarContaSujeito(string $id, array $dados): ApiResponse
    {
        foreach ($this->armazenamento['subjects'] ?? [] as $indice => $sujeito) {
            if (($sujeito['id'] ?? null) !== $id) {
                continue;
            }

            if (($sujeito['email'] ?? null) !== null) {
                return ApiResponse::falha(ErrorViewModelFactory::validacao(['email' => 'Este sujeito já possui conta.']));
            }

            $this->armazenamento['subjects'][$indice]['email'] = $dados['email'] ?? null;

            return ApiResponse::sucesso($this->armazenamento['subjects'][$indice]);
        }

        return ApiResponse::falha(ErrorViewModelFactory::naoEncontrado('subjects'));
    }

    /**
     * @param array<string, string> $dados
     */
    public function put(string $recurso, array $dados = []): ApiResponse
    {
        [$chave, $id] = self::partir($recurso);

        if ($this->falhaForcada !== null) {
            return ApiResponse::falha($this->erroPara($this->falhaForcada, $chave));
        }

        if ($id === null) {
            return ApiResponse::falha(ErrorViewModelFactory::naoEncontrado($chave));
        }

        foreach ($this->armazenamento[$chave] ?? [] as $indice => $item) {
            if (($item['id'] ?? null) === $id) {
                $this->armazenamento[$chave][$indice] = array_merge($item, $dados, ['id' => $id]);

                return ApiResponse::sucesso($this->armazenamento[$chave][$indice]);
            }
        }

        return ApiResponse::falha(ErrorViewModelFactory::naoEncontrado($chave));
    }

    public function delete(string $recurso): ApiResponse
    {
        [$chave, $id] = self::partir($recurso);

        if ($this->falhaForcada !== null) {
            return ApiResponse::falha($this->erroPara($this->falhaForcada, $chave));
        }

        if ($id === null) {
            return ApiResponse::falha(ErrorViewModelFactory::naoEncontrado($chave));
        }

        foreach ($this->armazenamento[$chave] ?? [] as $indice => $item) {
            if (($item['id'] ?? null) === $id) {
                unset($this->armazenamento[$chave][$indice]);

                return ApiResponse::sucesso([]);
            }
        }

        return ApiResponse::falha(ErrorViewModelFactory::naoEncontrado($chave));
    }

    /**
     * @return array<string, mixed>|null
     */
    private function localizar(string $chave, string $id): ?array
    {
        foreach ($this->armazenamento[$chave] ?? [] as $item) {
            if (($item['id'] ?? null) === $id) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @return array{0: string, 1: ?string}
     */
    private static function partir(string $recurso): array
    {
        $partes = explode('/', trim($recurso, '/'), 2);

        return [$partes[0], $partes[1] ?? null];
    }

    private function erroPara(ErrorType $tipo, string $recurso): ErrorViewModel
    {
        return match ($tipo) {
            ErrorType::COMUNICACAO => ErrorViewModelFactory::comunicacao($recurso),
            ErrorType::NAO_ENCONTRADO => ErrorViewModelFactory::naoEncontrado($recurso),
            ErrorType::INTERNO => ErrorViewModelFactory::interno(),
            ErrorType::VALIDACAO => ErrorViewModelFactory::validacao(['recurso' => 'Requisição inválida.']),
            ErrorType::TIMEOUT => ErrorViewModelFactory::timeout($recurso),
        };
    }
}
