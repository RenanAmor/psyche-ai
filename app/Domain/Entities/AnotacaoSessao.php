<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use DateTimeImmutable;
use PsycheAI\Domain\ValueObjects\ConteudoAnotacao;
use PsycheAI\Domain\ValueObjects\Identificador;

/**
 * Anotação livre do analista sobre uma Sessao — uma por Sessao (upsert por
 * sessaoId, não uma lista). Existe para a UX de autosave: o analista digita
 * durante/depois da consulta e o conteúdo é salvo sozinho, sem risco de
 * perda por fechar a aba sem clicar em "Salvar" (critério de aceite
 * explícito do produto).
 */
final class AnotacaoSessao extends Entity
{
    public function __construct(
        Identificador $id,
        private readonly string $sessaoId,
        private readonly ConteudoAnotacao $conteudo,
        private readonly DateTimeImmutable $atualizadoEm
    ) {
        parent::__construct($id);
    }

    public function sessaoId(): string
    {
        return $this->sessaoId;
    }

    public function conteudo(): ConteudoAnotacao
    {
        return $this->conteudo;
    }

    public function atualizadoEm(): DateTimeImmutable
    {
        return $this->atualizadoEm;
    }
}
