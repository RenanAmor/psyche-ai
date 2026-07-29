<?php

declare(strict_types=1);

namespace PsycheAI\Application\Exceptions;

/**
 * Lançada quando uma Application Service busca, atualiza ou remove um
 * agregado por id e o repositório de Domínio não o encontra.
 */
final class RecursoNaoEncontradoException extends ApplicationException
{
    public static function paraId(string $tipo, string $id): self
    {
        return new self(sprintf('%s com id "%s" não foi encontrado.', $tipo, $id));
    }
}
