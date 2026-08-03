<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\InscreverNaListaDeEspera;

use PsycheAI\Application\Contracts\ResultInterface;
use PsycheAI\Application\DTOs\InscricaoListaDeEsperaDTO;
use PsycheAI\Domain\Entities\InscricaoListaDeEspera;

final class InscreverNaListaDeEsperaResult implements ResultInterface
{
    public function __construct(
        private readonly InscricaoListaDeEspera $inscricao
    ) {
    }

    public function inscricao(): InscricaoListaDeEspera
    {
        return $this->inscricao;
    }

    public function dto(): InscricaoListaDeEsperaDTO
    {
        return InscricaoListaDeEsperaDTO::fromEntity($this->inscricao);
    }
}
