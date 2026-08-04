<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\GravacaoAudio;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Locutor;
use PsycheAI\Domain\ValueObjects\StatusTranscricao;

final class GravacaoAudioTest extends TestCase
{
    public function testComecaPendenteDeTranscricao(): void
    {
        $gravacao = new GravacaoAudio(
            new Identificador('g1'),
            'sessao-1',
            'sessoes/sessao-1.webm'
        );

        $this->assertSame('sessao-1', $gravacao->sessaoId());
        $this->assertSame('sessoes/sessao-1.webm', $gravacao->caminhoArmazenamento());
        $this->assertSame(StatusTranscricao::Pendente, $gravacao->status());
        $this->assertNull($gravacao->transcritaEm());
    }

    public function testLocutorPadraoESujeito(): void
    {
        $gravacao = new GravacaoAudio(new Identificador('g1'), 'sessao-1', 'sessoes/sessao-1.webm');

        $this->assertSame(Locutor::Sujeito, $gravacao->locutor());
    }

    public function testExposesLocutorExplicito(): void
    {
        $gravacao = new GravacaoAudio(
            new Identificador('g1'),
            'sessao-1',
            'sessoes/sessao-1.webm',
            locutor: Locutor::Analista
        );

        $this->assertSame(Locutor::Analista, $gravacao->locutor());
    }

    public function testMarcarTranscritaAtualizaStatusEData(): void
    {
        $gravacao = new GravacaoAudio(new Identificador('g1'), 'sessao-1', 'sessoes/sessao-1.webm');

        $gravacao->marcarTranscrita();

        $this->assertSame(StatusTranscricao::Transcrita, $gravacao->status());
        $this->assertNotNull($gravacao->transcritaEm());
    }

    public function testMarcarFalhaAtualizaStatus(): void
    {
        $gravacao = new GravacaoAudio(new Identificador('g1'), 'sessao-1', 'sessoes/sessao-1.webm');

        $gravacao->marcarFalha();

        $this->assertSame(StatusTranscricao::Falha, $gravacao->status());
    }
}
