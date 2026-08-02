<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\Http;

/**
 * Cache-busting automático pros assets estáticos (CSS/JS) de public/web/assets
 * — o CDN da Hostinger guarda esses arquivos por até 7 dias
 * (Cache-Control: max-age=604800), então sem um parâmetro de versão na URL
 * uma atualização de estilo.css fica presa em cache por dias pra parte dos
 * usuários. Usa filemtime() do próprio arquivo em vez de um número
 * incrementado manualmente: o mtime já muda sozinho a cada deploy/upload.
 */
final class AssetVersion
{
    private const RAIZ_PUBLICA = __DIR__ . '/../../../../public/web';

    public static function url(string $caminho): string
    {
        $caminhoAbsoluto = self::RAIZ_PUBLICA . $caminho;
        $versao = is_file($caminhoAbsoluto) ? (string) filemtime($caminhoAbsoluto) : '1';

        return BasePath::url($caminho) . '?v=' . $versao;
    }
}
