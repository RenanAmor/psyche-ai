<?php

declare(strict_types=1);

namespace PsycheAI\Domain\ValueObjects;

use DateTimeImmutable;

/**
 * Uma única ocorrência de um conteúdo normalizado (a mesma chave usada por
 * DetectorRecorrencias::detectar()) dentro da Memória Longitudinal de um
 * Sujeito — a unidade a partir da qual o circuito/trajeto de uma
 * Recorrencia através de Sessões distintas é reconstruído (revisão
 * pós-Sprint 16, "mapear a pulsão, todo o caminho").
 *
 * `momento` é a data da Sessao que contém a ocorrência, não o timestamp
 * técnico de criação do EventoDiscursivo — mesma ancoragem estrutural do
 * tempo já adotada desde a Sprint 13 (Discurso/Memória ancorados na data
 * da Sessao, nunca em um campo de tempo próprio).
 */
final class OcorrenciaRecorrencia extends ValueObject
{
    public function __construct(
        private readonly string $sessaoId,
        private readonly string $discursoId,
        private readonly string $eventoId,
        private readonly DateTimeImmutable $momento,
        private readonly int $posicao
    ) {
    }

    public function sessaoId(): string
    {
        return $this->sessaoId;
    }

    public function discursoId(): string
    {
        return $this->discursoId;
    }

    public function eventoId(): string
    {
        return $this->eventoId;
    }

    public function momento(): DateTimeImmutable
    {
        return $this->momento;
    }

    public function posicao(): int
    {
        return $this->posicao;
    }
}
