<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use PHPUnit\Framework\TestCase;
use PsycheAI\Application\Exceptions\ComandoInvalidoException;
use PsycheAI\Application\UseCases\RegistrarEventoDiscursivo\RegistrarEventoDiscursivoCommand;
use PsycheAI\Application\UseCases\RegistrarEventoDiscursivo\RegistrarEventoDiscursivoHandler;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\Locutor;

final class RegistrarEventoDiscursivoHandlerTest extends TestCase
{
    public function testRegistraEventoEAssociaAoDiscurso(): void
    {
        $discurso = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('Discurso de teste.'));

        $handler = new RegistrarEventoDiscursivoHandler();
        $result = $handler->handle(new RegistrarEventoDiscursivoCommand($discurso, 'e1', 'lapso', 3));

        $this->assertSame([$result->evento()], $discurso->eventos());
        $this->assertSame('e1', $result->dto()->id);
        $this->assertSame('lapso', $result->dto()->conteudo);
        $this->assertSame(3, $result->dto()->posicao);
        $this->assertSame('desconhecido', $result->dto()->locutor);
    }

    public function testRegistraEventoComLocutorExplicito(): void
    {
        $discurso = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('Discurso de teste.'));

        $handler = new RegistrarEventoDiscursivoHandler();
        $result = $handler->handle(new RegistrarEventoDiscursivoCommand($discurso, 'e1', 'lapso', 0, Locutor::Analista));

        $this->assertSame(Locutor::Analista, $result->evento()->locutor());
        $this->assertSame('analista', $result->dto()->locutor);
    }

    public function testLancaComandoInvalidoQuandoConteudoVazio(): void
    {
        $discurso = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('Discurso de teste.'));
        $handler = new RegistrarEventoDiscursivoHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new RegistrarEventoDiscursivoCommand($discurso, 'e1', '   ', 0));
    }

    public function testLancaComandoInvalidoQuandoPosicaoNegativa(): void
    {
        $discurso = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('Discurso de teste.'));
        $handler = new RegistrarEventoDiscursivoHandler();

        $this->expectException(ComandoInvalidoException::class);

        $handler->handle(new RegistrarEventoDiscursivoCommand($discurso, 'e1', 'lapso', -1));
    }
}
