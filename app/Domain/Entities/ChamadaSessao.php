<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use DateTimeImmutable;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\StatusChamada;

/**
 * A sala de videochamada (Daily.co) vinculada a uma Sessao, e o link
 * mágico (tokenAcesso) que o analista envia ao Sujeito para entrar sem
 * login/conta — mecanismo de acesso distinto de PortaoDeAnalista e
 * PortaoDeParticipante. `sessaoId` é único por design (uma sala por
 * Sessao, reaproveitada enquanto o token não expirar).
 */
final class ChamadaSessao extends Entity
{
    private StatusChamada $status;
    private ?DateTimeImmutable $encerradaEm;
    private ?DateTimeImmutable $processadaEm;

    public function __construct(
        Identificador $id,
        private readonly string $sessaoId,
        private readonly string $salaProvedorId,
        private readonly string $salaUrl,
        private readonly string $tokenAcesso,
        private readonly DateTimeImmutable $criadaEm,
        private readonly DateTimeImmutable $expiraEm,
        ?StatusChamada $status = null,
        ?DateTimeImmutable $encerradaEm = null,
        ?DateTimeImmutable $processadaEm = null
    ) {
        parent::__construct($id);

        $this->status = $status ?? StatusChamada::Criada;
        $this->encerradaEm = $encerradaEm;
        $this->processadaEm = $processadaEm;
    }

    public function sessaoId(): string
    {
        return $this->sessaoId;
    }

    public function salaProvedorId(): string
    {
        return $this->salaProvedorId;
    }

    public function salaUrl(): string
    {
        return $this->salaUrl;
    }

    public function tokenAcesso(): string
    {
        return $this->tokenAcesso;
    }

    public function status(): StatusChamada
    {
        return $this->status;
    }

    public function criadaEm(): DateTimeImmutable
    {
        return $this->criadaEm;
    }

    public function expiraEm(): DateTimeImmutable
    {
        return $this->expiraEm;
    }

    public function encerradaEm(): ?DateTimeImmutable
    {
        return $this->encerradaEm;
    }

    public function tokenValido(DateTimeImmutable $agora): bool
    {
        return $this->status !== StatusChamada::Encerrada && $agora < $this->expiraEm;
    }

    public function encerrar(DateTimeImmutable $quando): void
    {
        $this->status = StatusChamada::Encerrada;
        $this->encerradaEm = $quando;
    }

    public function processadaEm(): ?DateTimeImmutable
    {
        return $this->processadaEm;
    }

    /**
     * Distinto de status=Encerrada: a chamada pode estar encerrada há um
     * tempo antes do worker assíncrono (bin/processar-chamadas-de-video.php)
     * conseguir baixar e transcrever as trilhas — encerradaEm marca "a
     * chamada acabou", processadaEm marca "os EventoDiscursivo já existem".
     */
    public function marcarProcessada(DateTimeImmutable $quando): void
    {
        $this->processadaEm = $quando;
    }

    public function precisaSerProcessada(): bool
    {
        return $this->status === StatusChamada::Encerrada && $this->processadaEm === null;
    }
}
