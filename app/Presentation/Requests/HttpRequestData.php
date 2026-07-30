<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Requests;

use DateTimeImmutable;
use Exception;
use PsycheAI\Presentation\Http\HttpException;

/**
 * Base para os DTOs de entrada da camada HTTP: apenas validação estrutural
 * (campo presente, tipo primitivo correto) — nunca regras de negócio, que
 * permanecem nos Value Objects do Domínio quando o Command/Handler é
 * executado pela Application Service.
 */
abstract class HttpRequestData
{
    /**
     * @param array<string, mixed> $dados
     */
    protected static function exigirString(array $dados, string $campo): string
    {
        $valor = $dados[$campo] ?? null;

        if (!is_string($valor) || trim($valor) === '') {
            throw HttpException::badRequest(sprintf('O campo "%s" é obrigatório.', $campo));
        }

        return $valor;
    }

    /**
     * @param array<string, mixed> $dados
     */
    protected static function exigirInteiro(array $dados, string $campo): int
    {
        $valor = $dados[$campo] ?? null;

        if (is_int($valor)) {
            return $valor;
        }

        if (is_string($valor) && $valor !== '' && ctype_digit(ltrim($valor, '-'))) {
            return (int) $valor;
        }

        throw HttpException::badRequest(sprintf('O campo "%s" deve ser um número inteiro.', $campo));
    }

    /**
     * @param array<string, mixed> $dados
     */
    protected static function exigirData(array $dados, string $campo): DateTimeImmutable
    {
        $valor = self::exigirString($dados, $campo);

        try {
            return new DateTimeImmutable($valor);
        } catch (Exception) {
            throw HttpException::badRequest(sprintf('O campo "%s" deve ser uma data válida.', $campo));
        }
    }

    /**
     * Variante opcional de exigirString: ausente ou vazio devolve null em
     * vez de lançar exceção — usada em parâmetros de consulta (query
     * string), que ao contrário do corpo de escrita nunca são
     * obrigatórios.
     *
     * @param array<string, mixed> $dados
     */
    protected static function opcionalString(array $dados, string $campo): ?string
    {
        $valor = $dados[$campo] ?? null;

        if ($valor === null || (is_string($valor) && trim($valor) === '')) {
            return null;
        }

        if (!is_string($valor)) {
            throw HttpException::badRequest(sprintf('O campo "%s" deve ser um texto.', $campo));
        }

        return $valor;
    }

    /**
     * @param array<string, mixed> $dados
     */
    protected static function opcionalData(array $dados, string $campo): ?DateTimeImmutable
    {
        $valor = self::opcionalString($dados, $campo);

        if ($valor === null) {
            return null;
        }

        try {
            return new DateTimeImmutable($valor);
        } catch (Exception) {
            throw HttpException::badRequest(sprintf('O campo "%s" deve ser uma data válida.', $campo));
        }
    }

    /**
     * @param array<string, mixed> $dados
     */
    protected static function opcionalInteiroPositivo(array $dados, string $campo, int $padrao): int
    {
        $valor = $dados[$campo] ?? null;

        if ($valor === null || $valor === '') {
            return $padrao;
        }

        if (is_string($valor) && ctype_digit($valor)) {
            $valor = (int) $valor;
        }

        if (!is_int($valor) || $valor < 1) {
            throw HttpException::badRequest(sprintf('O campo "%s" deve ser um número inteiro maior que zero.', $campo));
        }

        return $valor;
    }
}
