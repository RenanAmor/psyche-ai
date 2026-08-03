<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use DateTimeImmutable;
use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;

/**
 * Conta real do participante da pesquisa — o público que acessa a ECO
 * (`/conversa*`), protegida por `Presentation/Web/Security/PortaoDeParticipante`.
 * Deliberadamente independente de `Analista` (Portão/tabela/sessão próprios)
 * e das colunas `email`/`senha_hash` de `Sujeito` (Sprint 20, self-signup
 * público, substituída por este provisionamento feito pelo Analista). Não é
 * um conceito do Modelo Computacional do Discurso: como Analista, é uma
 * conta de acesso ao sistema, sem significado psicanalítico — o vínculo com
 * o Sujeito/discurso acontece em tempo de execução (o id do Participante
 * autenticado é usado como id do Sujeito), nunca no schema.
 */
final class Participante extends Entity
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
