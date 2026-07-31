<?php

declare(strict_types=1);

namespace PsycheAI\Transport\DTO;

/**
 * Resultado do transporte de um item individual (arquivo ou diretório
 * protegido) para produção.
 *
 * status possíveis:
 * - transported: arquivo enviado e verificado com sucesso.
 * - already_current: produção já tinha o mesmo conteúdo (tamanho igual).
 * - conflict: já existe arquivo remoto com tamanho diferente do local —
 *   upload abortado para não sobrescrever silenciosamente. Arquivos
 *   remotos com o mesmo tamanho são tratados como already_current sem
 *   download de verificação (diferente do Collector369, que confirma por
 *   hash mesmo com tamanho igual — aqui a árvore tem milhares de arquivos
 *   de vendor/, e download+hash em cada um tornaria o transporte
 *   impraticavelmente lento; a verificação por hash continua acontecendo
 *   sempre logo após um upload novo, para detectar corrupção na transferência).
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

    public static function conflict(string $relativePath, string $message): self
    {
        return new self($relativePath, 'conflict', $message);
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
