<?php

declare(strict_types=1);

namespace PsycheAI\Presentation\Web\ViewModels;

final class InscricaoListaDeEsperaViewModel
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $nome,
        public readonly string $profissao = '',
        public readonly string $instituicao = '',
        public readonly string $paisEstado = '',
        public readonly string $motivoInteresse = '',
        public readonly bool $aceitouPoliticaPrivacidade = false,
        public readonly bool $aceitouTermoConsentimento = false,
        public readonly string $criadoEm = ''
    ) {
    }

    /**
     * @param array<string, mixed> $dados
     */
    public static function fromArray(array $dados): self
    {
        return new self(
            id: (string) ($dados['id'] ?? ''),
            email: (string) ($dados['email'] ?? ''),
            nome: (string) ($dados['nome'] ?? ''),
            profissao: (string) ($dados['profissao'] ?? ''),
            instituicao: (string) ($dados['instituicao'] ?? ''),
            paisEstado: (string) ($dados['paisEstado'] ?? ''),
            motivoInteresse: (string) ($dados['motivoInteresse'] ?? ''),
            aceitouPoliticaPrivacidade: (bool) ($dados['aceitouPoliticaPrivacidade'] ?? false),
            aceitouTermoConsentimento: (bool) ($dados['aceitouTermoConsentimento'] ?? false),
            criadoEm: (string) ($dados['criadoEm'] ?? '')
        );
    }

    /**
     * @param array<int, array<string, mixed>> $lista
     * @return self[]
     */
    public static function fromArrayList(array $lista): array
    {
        return array_map(self::fromArray(...), $lista);
    }
}
