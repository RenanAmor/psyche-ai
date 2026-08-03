<?php

declare(strict_types=1);

namespace PsycheAI\Application\DTOs;

use PsycheAI\Domain\Entities\InscricaoListaDeEspera;

final class InscricaoListaDeEsperaDTO
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
        public readonly bool $aceitouTermoConsentimento,
        public readonly string $criadoEm
    ) {
    }

    public static function fromEntity(InscricaoListaDeEspera $inscricao): self
    {
        return new self(
            id: $inscricao->id()->valor(),
            email: $inscricao->email()->valor(),
            nome: $inscricao->nome(),
            profissao: $inscricao->profissao(),
            instituicao: $inscricao->instituicao(),
            paisEstado: $inscricao->paisEstado(),
            motivoInteresse: $inscricao->motivoInteresse(),
            aceitouPoliticaPrivacidade: $inscricao->aceitouPoliticaPrivacidade(),
            aceitouTermoConsentimento: $inscricao->aceitouTermoConsentimento(),
            criadoEm: $inscricao->criadoEm()->format(DATE_ATOM)
        );
    }
}
