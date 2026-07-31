<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use PHPUnit\Framework\TestCase;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Application\UseCases\RegistrarGravacaoAudio\RegistrarGravacaoAudioCommand;
use PsycheAI\Application\UseCases\RegistrarGravacaoAudio\RegistrarGravacaoAudioHandler;
use PsycheAI\Domain\ValueObjects\StatusTranscricao;

final class RegistrarGravacaoAudioHandlerTest extends TestCase
{
    public function testRegistraGravacaoPendenteDeTranscricao(): void
    {
        $handler = new RegistrarGravacaoAudioHandler();

        $result = $handler->handle(new RegistrarGravacaoAudioCommand('g1', 'sessao-1', 'sessoes/sessao-1.webm'));

        $this->assertSame('sessao-1', $result->gravacao()->sessaoId());
        $this->assertSame('sessoes/sessao-1.webm', $result->gravacao()->caminhoArmazenamento());
        $this->assertSame(StatusTranscricao::Pendente, $result->gravacao()->status());
    }

    public function testLancaComandoInvalidoQuandoIdVazio(): void
    {
        $handler = new RegistrarGravacaoAudioHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new RegistrarGravacaoAudioCommand('', 'sessao-1', 'sessoes/sessao-1.webm'));
    }
}
