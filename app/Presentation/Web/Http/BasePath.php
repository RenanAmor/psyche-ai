<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Http;

/**
 * Prefixo de base opcional (ex.: "/psycheai") para a interface web rodar
 * sob um subcaminho de um domínio compartilhado — só a Web precisa
 * disso; a API REST continua servidor-a-servidor, nunca exposta direto
 * ao navegador. Holder estático deliberado: um único valor por
 * requisição, definido uma vez em `public/web/index.php` antes de
 * qualquer rota ser despachada ou link gerado, no mesmo espírito de
 * `$_SESSION`/`$_COOKIE`.
 */
final class BasePath
{
    private static string $valor = '';

    public static function definir(string $prefixo): void
    {
        self::$valor = rtrim($prefixo, '/');
    }

    /**
     * Antepõe o prefixo a um caminho absoluto (começando com "/") — usado
     * em todo lugar que gera href/action/cookie path/asset da própria
     * aplicação. Sem prefixo configurado, devolve o caminho como está.
     */
    public static function url(string $caminho): string
    {
        return self::$valor . $caminho;
    }

    public static function valor(): string
    {
        return self::$valor;
    }
}
