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
 *
 * Confirmado em produção (03/08/2026): o `?v=` sozinho não é suficiente —
 * a camada de cache de origem da Hostinger (LiteSpeed/hcdn) guarda a
 * resposta de arquivo estático por caminho, ignorando a query string
 * (testado com valores de `?v=` novos e nunca vistos, mesmo resultado
 * antigo todas as vezes). Por isso `estilo.css` foi renomeado pra
 * `estilo.2.css` nessa data — mudar o caminho em si é o único jeito
 * confirmado de forçar uma chave de cache nova nessa infraestrutura.
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
