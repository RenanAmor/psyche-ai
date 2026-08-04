<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\RegistrarAnotacaoSessao;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Application\DTOs\AnotacaoSessaoDTO;
use PsycheAI\Domain\Entities\AnotacaoSessao;

final class RegistrarAnotacaoSessaoResult implements ResultInterface
{
    public function __construct(
        private readonly AnotacaoSessao $anotacao
    ) {
    }

    public function anotacao(): AnotacaoSessao
    {
        return $this->anotacao;
    }

    public function dto(): AnotacaoSessaoDTO
    {
        return AnotacaoSessaoDTO::fromEntity($this->anotacao);
    }
}
