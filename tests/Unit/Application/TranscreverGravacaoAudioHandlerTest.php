<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use PHPUnit\Framework\TestCase;
use PsycheAI\Application\UseCases\TranscreverGravacaoAudio\TranscreverGravacaoAudioCommand;
use PsycheAI\Application\UseCases\TranscreverGravacaoAudio\TranscreverGravacaoAudioHandler;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Entities\GravacaoAudio;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;
use PsycheAI\Domain\ValueObjects\StatusTranscricao;

final class TranscreverGravacaoAudioHandlerTest extends TestCase
{
    public function testCriaUmEventoDiscursivoPorSegmentoNaOrdem(): void
    {
        $discurso = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('Conversa'));
        $gravacao = new GravacaoAudio(new Identificador('g1'), 'sessao-1', 'sessoes/sessao-1.webm');

        $handler = new TranscreverGravacaoAudioHandler();
        $result = $handler->handle(new TranscreverGravacaoAudioCommand(
            $discurso,
            $gravacao,
            [
                ['id' => 'e1', 'texto' => 'eu quis dizer'],
                ['id' => 'e2', 'texto' => 'quero dizer'],
            ]
        ));

        $this->assertCount(2, $result->eventos());
        $this->assertSame('eu quis dizer', $result->eventos()[0]->conteudo()->valor());
        $this->assertSame(0, $result->eventos()[0]->posicao()->valor());
        $this->assertSame('quero dizer', $result->eventos()[1]->conteudo()->valor());
        $this->assertSame(1, $result->eventos()[1]->posicao()->valor());
        $this->assertSame([$result->eventos()[0], $result->eventos()[1]], $discurso->eventos());
    }

    public function testContinuaAPartirDaProximaPosicaoDisponivel(): void
    {
        $discurso = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('Conversa'));
        $discurso->adicionarEvento(new EventoDiscursivo(new Identificador('e0'), new ConteudoDiscursivo('já digitado'), new Posicao(0)));
        $gravacao = new GravacaoAudio(new Identificador('g1'), 'sessao-1', 'sessoes/sessao-1.webm');

        $handler = new TranscreverGravacaoAudioHandler();
        $result = $handler->handle(new TranscreverGravacaoAudioCommand(
            $discurso,
            $gravacao,
            [['id' => 'e1', 'texto' => 'falado depois']]
        ));

        $this->assertSame(1, $result->eventos()[0]->posicao()->valor());
    }

    public function testMarcaAGravacaoComoTranscrita(): void
    {
        $discurso = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('Conversa'));
        $gravacao = new GravacaoAudio(new Identificador('g1'), 'sessao-1', 'sessoes/sessao-1.webm');

        $handler = new TranscreverGravacaoAudioHandler();
        $result = $handler->handle(new TranscreverGravacaoAudioCommand($discurso, $gravacao, [['id' => 'e1', 'texto' => 'falado']]));

        $this->assertSame(StatusTranscricao::Transcrita, $result->gravacao()->status());
        $this->assertNotNull($result->gravacao()->transcritaEm());
    }
}
