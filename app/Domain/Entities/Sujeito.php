<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use PsycheAI\Domain\ValueObjects\Email;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\NomeSujeito;

final class Sujeito extends Entity
{
    /**
     * @var Sessao[]
     */
    private array $sessoes = [];

    /**
     * Sprint 20 ("Contas reais do Sujeito"): $email/$senhaHash são
     * opcionais porque a maioria dos Sujeitos continua anônima por
     * cookie (`psyche_pessoa_id`, Sprint 17) — só ganham valor quando o
     * Sujeito se cadastra para ter um espaço singular estável, que
     * sobreviva a troca de navegador/dispositivo.
     */
    public function __construct(
        Identificador $id,
        private readonly NomeSujeito $nome,
        private readonly ?Email $email = null,
        private readonly ?string $senhaHash = null
    ) {
        parent::__construct($id);
    }

    public function nome(): NomeSujeito
    {
        return $this->nome;
    }

    public function email(): ?Email
    {
        return $this->email;
    }

    public function temConta(): bool
    {
        return $this->email !== null;
    }

    public function verificarSenha(string $senha): bool
    {
        return $this->senhaHash !== null && password_verify($senha, $this->senhaHash);
    }

    /**
     * Exposto só para a Infraestrutura de persistência gravar o hash —
     * nunca para a Application/Presentation, que devem usar
     * verificarSenha() (mesmo espírito de Analista::senhaHash()).
     */
    public function senhaHash(): ?string
    {
        return $this->senhaHash;
    }

    public function adicionarSessao(Sessao $sessao): void
    {
        $this->sessoes[] = $sessao;
    }

    /**
     * @return Sessao[]
     */
    public function sessoes(): array
    {
        return $this->sessoes;
    }
}