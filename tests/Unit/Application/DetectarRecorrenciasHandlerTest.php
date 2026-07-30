<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Application\UseCases\DetectarRecorrencias\DetectarRecorrenciasCommand;
use PsycheAI\Application\UseCases\DetectarRecorrencias\DetectarRecorrenciasHandler;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Entities\MemoriaLongitudinal;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Posicao;

final class DetectarRecorrenciasHandlerTest extends TestCase
{
    private function construirMemoriaComEventos(): MemoriaLongitudinal
    {
        $discurso = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('conteudo do discurso'));
        $discurso->adicionarEvento(new EventoDiscursivo(new Identificador('e1'), new ConteudoDiscursivo('lapso'), new Posicao(0)));
        $discurso->adicionarEvento(new EventoDiscursivo(new Identificador('e2'), new ConteudoDiscursivo('lapso'), new Posicao(1)));
        $discurso->adicionarEvento(new EventoDiscursivo(new Identificador('e3'), new ConteudoDiscursivo('chiste'), new Posicao(2)));

        $sessao = new Sessao(new Identificador('s1'), new DataSessao(new DateTimeImmutable()));
        $sessao->adicionarDiscurso($discurso);

        $memoria = new MemoriaLongitudinal(new Identificador('mem1'));
        $memoria->adicionarSessao($sessao);

        return $memoria;
    }

    public function testDetectaApenasRecorrenciasComFrequenciaMinima(): void
    {
        $memoria = $this->construirMemoriaComEventos();

        $handler = new DetectarRecorrenciasHandler();
        $result = $handler->handle(new DetectarRecorrenciasCommand($memoria));

        $recorrencias = $result->recorrencias();
        $this->assertCount(1, $recorrencias);
        $this->assertSame('lapso', $recorrencias[0]->descricao()->valor());
        $this->assertSame(2, $recorrencias[0]->frequencia()->valor());
    }

    public function testRespeitaMinimoPersonalizado(): void
    {
        $memoria = $this->construirMemoriaComEventos();

        $handler = new DetectarRecorrenciasHandler();
        $result = $handler->handle(new DetectarRecorrenciasCommand($memoria, minimo: 1));

        $descricoes = array_map(
            static fn ($recorrencia) => $recorrencia->descricao()->valor(),
            $result->recorrencias()
        );

        $this->assertSame(['lapso', 'chiste'], $descricoes);
    }

    public function testNormalizaVariacoesDeGrafiaComoAMesmaRecorrencia(): void
    {
        $discurso = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('conteudo do discurso'));
        $discurso->adicionarEvento(new EventoDiscursivo(new Identificador('e1'), new ConteudoDiscursivo('lapso'), new Posicao(0)));
        $discurso->adicionarEvento(new EventoDiscursivo(new Identificador('e2'), new ConteudoDiscursivo(' Lapso'), new Posicao(1)));

        $sessao = new Sessao(new Identificador('s1'), new DataSessao(new DateTimeImmutable()));
        $sessao->adicionarDiscurso($discurso);

        $memoria = new MemoriaLongitudinal(new Identificador('mem1'));
        $memoria->adicionarSessao($sessao);

        $handler = new DetectarRecorrenciasHandler();
        $result = $handler->handle(new DetectarRecorrenciasCommand($memoria));

        $recorrencias = $result->recorrencias();
        $this->assertCount(1, $recorrencias);
        $this->assertSame('lapso', $recorrencias[0]->descricao()->valor());
        $this->assertSame(2, $recorrencias[0]->frequencia()->valor());
    }
}
