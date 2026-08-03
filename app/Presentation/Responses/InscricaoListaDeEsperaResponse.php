<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Responses;

use PsycheAI\Application\DTOs\InscricaoListaDeEsperaDTO;

final class InscricaoListaDeEsperaResponse
{
    private function __construct(
        private readonly InscricaoListaDeEsperaDTO $dto
    ) {
    }

    public static function fromDTO(InscricaoListaDeEsperaDTO $dto): self
    {
        return new self($dto);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->dto->id,
            'email' => $this->dto->email,
            'nome' => $this->dto->nome,
            'profissao' => $this->dto->profissao,
            'instituicao' => $this->dto->instituicao,
            'paisEstado' => $this->dto->paisEstado,
            'motivoInteresse' => $this->dto->motivoInteresse,
            'aceitouPoliticaPrivacidade' => $this->dto->aceitouPoliticaPrivacidade,
            'aceitouTermoConsentimento' => $this->dto->aceitouTermoConsentimento,
            'criadoEm' => $this->dto->criadoEm,
        ];
    }
}
