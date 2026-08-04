<?php

declare(strict_types=1);

namespace PsycheAI\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Texto livre do analista sobre uma Sessao (autosave). Diferente de
 * ObservacaoTexto, aceita string vazia deliberadamente — um rascunho de
 * autosave passa pelo estado vazio o tempo todo (ex.: usuário apagou tudo
 * antes de escrever de novo), e isso nunca deve ser tratado como erro de
 * validação.
 */
final class ConteudoAnotacao extends ValueObject
{
    private const TAMANHO_MAXIMO = 50000;

    public function __construct(
        private readonly string $valor
    ) {
        if (mb_strlen($valor) > self::TAMANHO_MAXIMO) {
            throw new InvalidArgumentException(
                sprintf('O conteúdo da anotação não pode exceder %d caracteres.', self::TAMANHO_MAXIMO)
            );
        }
    }

    public function valor(): string
    {
        return $this->valor;
    }

    public function __toString(): string
    {
        return $this->valor;
    }
}
