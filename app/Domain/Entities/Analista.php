<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use DateTimeImmutable;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;

/**
 * Conta real do analista/administrador (Sprint 18, "Plataforma") — o
 * público que hoje passa pelo Portão (`Presentation/Web/Security/PortaoDeAnalista`).
 * Não é um conceito do Modelo Computacional do Discurso: ao contrário de
 * Sujeito/Sessao/Discurso, Analista é uma conta de acesso ao sistema, sem
 * nenhum significado psicanalítico. Um único papel nesta passagem — sem
 * permissões/administração granulares (ver docs/Roadmap.md).
 */
final class Analista extends Entity
{
    public function __construct(
        Identificador $id,
        private readonly Email $email,
        private readonly string $senhaHash,
        private readonly DateTimeImmutable $criadoEm
    ) {
        parent::__construct($id);
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function senhaHash(): string
    {
        return $this->senhaHash;
    }

    public function criadoEm(): DateTimeImmutable
    {
        return $this->criadoEm;
    }

    public function verificarSenha(string $senha): bool
    {
        return password_verify($senha, $this->senhaHash);
    }
}
