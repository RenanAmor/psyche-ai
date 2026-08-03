<?php

declare(strict_types=1);

namespace PsycheAI\Application\UseCases\InscreverNaListaDeEspera;

use PsycheAI\Application\Contracts\CommandInterface;

final class InscreverNaListaDeEsperaCommand implements CommandInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $nome,
        public readonly ?string $profissao,
        public readonly ?string $instituicao,
        public readonly string $paisEstado,
        public readonly string $motivoInteresse,
        public readonly bool $aceitouPoliticaPrivacidade,
        public readonly bool $aceitouTermoConsentimento
    ) {
    }
}
