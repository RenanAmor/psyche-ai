<?php

declare(strict_types=1);

namespace PsycheAI\Domain\Entities;

use DateTimeImmutable;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Locutor;
use PsycheAI\Domain\ValueObjects\StatusTranscricao;

/**
 * Sprint 22 (Captura de Áudio da Sessão): a gravação contínua e bruta de
 * uma Sessao inteira — falada, não digitada. Distinta de EventoDiscursivo
 * (que é o conteúdo já textual, um por segmento transcrito): a
 * GravacaoAudio é a fonte primária, preservada intacta (Regra 2) para que
 * o analista possa ouvir o original e validar o que o pipeline de
 * transcrição escreveu. `caminhoArmazenamento` aponta para o arquivo no
 * StorageInterface — a Entidade nunca carrega os bytes do áudio em si.
 */
final class GravacaoAudio extends Entity
{
    private readonly DateTimeImmutable $criadaEm;
    private StatusTranscricao $status;
    private ?DateTimeImmutable $transcritaEm;

    public function __construct(
        Identificador $id,
        private readonly string $sessaoId,
        private readonly string $caminhoArmazenamento,
        ?StatusTranscricao $status = null,
        ?DateTimeImmutable $criadaEm = null,
        ?DateTimeImmutable $transcritaEm = null,
        private readonly Locutor $locutor = Locutor::Sujeito
    ) {
        parent::__construct($id);

        $this->status = $status ?? StatusTranscricao::Pendente;
        $this->criadaEm = $criadaEm ?? new DateTimeImmutable();
        $this->transcritaEm = $transcritaEm;
    }

    public function sessaoId(): string
    {
        return $this->sessaoId;
    }

    public function caminhoArmazenamento(): string
    {
        return $this->caminhoArmazenamento;
    }

    public function status(): StatusTranscricao
    {
        return $this->status;
    }

    public function criadaEm(): DateTimeImmutable
    {
        return $this->criadaEm;
    }

    public function transcritaEm(): ?DateTimeImmutable
    {
        return $this->transcritaEm;
    }

    public function locutor(): Locutor
    {
        return $this->locutor;
    }

    public function marcarTranscrita(?DateTimeImmutable $transcritaEm = null): void
    {
        $this->status = StatusTranscricao::Transcrita;
        $this->transcritaEm = $transcritaEm ?? new DateTimeImmutable();
    }

    public function marcarFalha(): void
    {
        $this->status = StatusTranscricao::Falha;
    }
}
