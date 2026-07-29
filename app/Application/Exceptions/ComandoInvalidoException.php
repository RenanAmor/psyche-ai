<?php

declare(strict_types=1);

namespace PsycheAI\Application\Exceptions;

use InvalidArgumentException;
use Throwable;

/**
 * Lançada quando os dados primitivos de um Command não satisfazem os
 * invariantes exigidos pelos Value Objects do domínio.
 */
final class ComandoInvalidoException extends ApplicationException
{
    public static function fromInvalidArgument(InvalidArgumentException $erro): self
    {
        return new self($erro->getMessage(), previous: $erro);
    }

    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, previous: $previous);
    }
}
