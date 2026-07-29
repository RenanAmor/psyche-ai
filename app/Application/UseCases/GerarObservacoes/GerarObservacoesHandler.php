<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\GerarObservacoes;

use PsycheAI\Application\Contracts\UseCaseInterface;
use PsycheAI\Domain\Services\GeradorObservacoes;

final class GerarObservacoesHandler implements UseCaseInterface
{
    public function __construct(
        private readonly GeradorObservacoes $geradorObservacoes = new GeradorObservacoes()
    ) {
    }

    public function handle(GerarObservacoesCommand $command): GerarObservacoesResult
    {
        $observacoes = $this->geradorObservacoes->gerar($command->recorrencias);

        return new GerarObservacoesResult($observacoes);
    }
}
