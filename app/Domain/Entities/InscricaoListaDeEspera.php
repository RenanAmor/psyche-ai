<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use DateTimeImmutable;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;

/**
 * Registro de interesse na ECO por quem esbarra no login do Participante
 * sem conta (`/participante/entrar`, sem autocadastro público). Sem
 * senha, sem sessão, sem Portão próprio: puro registro de interesse,
 * totalmente independente de Participante/Analista. Os aceites de
 * Política de Privacidade e do TCLE (inicial, antes do TCLE completo
 * apresentado na provisão real de conta) são condição de existência do
 * registro — a Application Service nunca persiste uma inscrição sem
 * ambos em `true`. O provisionamento de conta real continua manual, via
 * bin/criar-participante.php.
 */
final class InscricaoListaDeEspera extends Entity
{
    public function __construct(
        Identificador $id,
        private readonly Email $email,
        private readonly string $nome,
        private readonly ?string $profissao,
        private readonly ?string $instituicao,
        private readonly string $paisEstado,
        private readonly string $motivoInteresse,
        private readonly bool $aceitouPoliticaPrivacidade,
        private readonly bool $aceitouTermoConsentimento,
        private readonly DateTimeImmutable $criadoEm
    ) {
        parent::__construct($id);
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function nome(): string
    {
        return $this->nome;
    }

    public function profissao(): ?string
    {
        return $this->profissao;
    }

    public function instituicao(): ?string
    {
        return $this->instituicao;
    }

    public function paisEstado(): string
    {
        return $this->paisEstado;
    }

    public function motivoInteresse(): string
    {
        return $this->motivoInteresse;
    }

    public function aceitouPoliticaPrivacidade(): bool
    {
        return $this->aceitouPoliticaPrivacidade;
    }

    public function aceitouTermoConsentimento(): bool
    {
        return $this->aceitouTermoConsentimento;
    }

    public function criadoEm(): DateTimeImmutable
    {
        return $this->criadoEm;
    }
}
