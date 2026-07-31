<?php

declare(strict_types=1);

namespace PsycheAI\Transport\DTO;

/**
 * Resultado do transporte de um item individual (arquivo ou diretório
 * protegido) para produção.
 *
 * status possíveis:
 * - transported: arquivo enviado (novo ou substituindo um remoto de
 *   tamanho diferente) e verificado com sucesso — local é sempre a fonte
 *   da verdade, então tamanho remoto diferente do local é tratado como
 *   "o arquivo mudou desde o último deploy" e sobrescrito, não como
 *   conflito (diferente do transporte do Collector369, onde cada nome de
 *   arquivo já embute um timestamp e uma colisão de nome é uma anomalia
 *   real que exige investigação humana — aqui os caminhos são fixos,
 *   é código versionado em git, e mudar é o caso normal de um redeploy).
 * - already_current: produção já tinha o mesmo tamanho do arquivo local,
 *   tratado como idêntico sem download de verificação (diferente do
 *   Collector369, que confirma por hash mesmo com tamanho igual — aqui a
 *   árvore tem milhares de arquivos de vendor/, e download+hash em cada
 *   um tornaria o transporte impraticavelmente lento; a verificação por
 *   hash continua acontecendo sempre logo após um upload/substituição,
 *   para detectar corrupção na transferência).
 * - error: falha técnica (conexão, integridade pós-upload, rename etc.).
 * - storage_dir_ensured: subdiretório protegido de storage/ garantido
 *   (criado ou já existente) — nenhum arquivo é tocado dentro dele.
 */
final class FileTransportResult
{
    private function __construct(
        public readonly string $relativePath,
        public readonly string $status,
        public readonly string $message,
        public readonly ?int $bytes = null,
    ) {
    }

    public static function transported(string $relativePath, int $bytes): self
    {
        return new self(
            $relativePath,
            'transported',
            "Arquivo transportado e verificado com sucesso ({$bytes} bytes).",
            $bytes,
        );
    }

    public static function alreadyCurrent(string $relativePath): self
    {
        return new self(
            $relativePath,
            'already_current',
            'Produção já está atualizada (mesmo tamanho do arquivo local).',
        );
    }

    public static function error(string $relativePath, string $message): self
    {
        return new self($relativePath, 'error', $message);
    }

    public static function storageDirEnsured(string $relativePath): self
    {
        return new self($relativePath, 'storage_dir_ensured', 'Diretório protegido garantido — conteúdo nunca é sobrescrito.');
    }
}
