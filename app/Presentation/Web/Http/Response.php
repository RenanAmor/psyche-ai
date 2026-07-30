<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Http;

/**
 * Resposta HTML da interface web. Distinta da Response da API REST
 * (Presentation\Http\Response, que é JSON) — aqui o corpo é sempre um
 * documento HTML já renderizado pelo ViewRenderer.
 */
final class Response
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        public readonly string $corpo,
        public readonly int $status = 200,
        public readonly array $headers = ['Content-Type' => 'text/html; charset=utf-8']
    ) {
    }

    public static function ok(string $corpo): self
    {
        return new self($corpo, 200);
    }

    /**
     * Resposta JSON para o fetch() de atualização incremental da tela de
     * Conversa (Sprint 17) — o único consumidor desta variante. O corpo
     * já vem em HTML pronto (fragmento renderizado pelo mesmo
     * ViewRenderer/Components da página cheia) dentro do payload, para
     * que nenhuma lógica de montagem de mensagem seja duplicada em JS.
     *
     * @param array<string, mixed> $dados
     */
    public static function json(array $dados, int $status = 200): self
    {
        return new self(
            (string) json_encode($dados, JSON_UNESCAPED_UNICODE),
            $status,
            ['Content-Type' => 'application/json; charset=utf-8']
        );
    }

    /**
     * Redirecionamento 302 usado pelo Portão do Analista (revisão
     * pós-Sprint 16): sem corpo, apenas o cabeçalho Location.
     */
    public static function redirecionar(string $local): self
    {
        return new self('', 302, ['Location' => $local]);
    }

    public static function naoEncontrado(string $corpo): self
    {
        return new self($corpo, 404);
    }

    public static function erroValidacao(string $corpo): self
    {
        return new self($corpo, 422);
    }

    public static function erroServico(string $corpo): self
    {
        return new self($corpo, 502);
    }

    public static function erroInterno(string $corpo): self
    {
        return new self($corpo, 500);
    }

    public function enviar(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $nome => $valor) {
            header(sprintf('%s: %s', $nome, $valor));
        }

        echo $this->corpo;
    }
}
