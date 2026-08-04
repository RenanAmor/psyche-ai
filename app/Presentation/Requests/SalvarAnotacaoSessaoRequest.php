<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

use PsycheAI\Presentation\Http\HttpException;

/**
 * Diferente dos demais Requests, não usa exigirString() para "conteudo":
 * autosave passa pelo estado vazio o tempo todo (ex.: o analista apagou
 * tudo antes de escrever de novo) — string vazia é um valor válido, não
 * um erro de validação.
 */
final class SalvarAnotacaoSessaoRequest extends HttpRequestData
{
    public function __construct(
        public readonly string $conteudo
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        $valor = $dados['conteudo'] ?? '';

        if (!is_string($valor)) {
            throw HttpException::badRequest('O campo "conteudo" deve ser um texto.');
        }

        return new self($valor);
    }
}
