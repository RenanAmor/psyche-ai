<?php

declare(strict_types=1);

namespace PsycheAI\Application\Exceptions;

use Throwable;

/**
 * Lançada quando GravacaoAudioApplicationService::transcrever() não
 * consegue completar o pipeline (falha ao ler o áudio armazenado ou
 * falha do provedor de transcrição) — a GravacaoAudio já foi marcada como
 * Falha antes desta exceção ser lançada, para que o worker (Sprint 22)
 * possa tentar novamente mais tarde sem reprocessar as já concluídas.
 */
final class TranscricaoFalhouException extends ApplicationException
{
    public static function paraGravacao(string $gravacaoId, Throwable $causa): self
    {
        return new self(
            sprintf('Falha ao transcrever a gravação "%s": %s', $gravacaoId, $causa->getMessage()),
            previous: $causa
        );
    }

    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, previous: $previous);
    }
}
