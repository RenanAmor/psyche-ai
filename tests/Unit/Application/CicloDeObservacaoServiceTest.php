<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Application\Services\CicloDeObservacaoService;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\EventoDiscursivo;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Entities\Sujeito;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\NomeSujeito;
use PsycheAI\Domain\ValueObjects\Posicao;

final class CicloDeObservacaoServiceTest extends TestCase
{
    public function testExecutaOCicloCompletoDeMemoriaARecorrenciasEObservacoes(): void
    {
        $sujeito = new Sujeito(new Identificador('1'), new NomeSujeito('Sujeito Um'));

        $sessao1 = new Sessao(new Identificador('s1'), new DataSessao(new DateTimeImmutable('2026-01-01')));
        $discurso1 = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('discurso 1'));
        $discurso1->adicionarEvento(new EventoDiscursivo(new Identificador('e1'), new ConteudoDiscursivo('lapso'), new Posicao(0)));
        $sessao1->adicionarDiscurso($discurso1);

        $sessao2 = new Sessao(new Identificador('s2'), new DataSessao(new DateTimeImmutable('2026-02-01')));
        $discurso2 = new Discurso(new Identificador('d2'), new ConteudoDiscursivo('discurso 2'));
        $discurso2->adicionarEvento(new EventoDiscursivo(new Identificador('e2'), new ConteudoDiscursivo('lapso'), new Posicao(0)));
        $sessao2->adicionarDiscurso($discurso2);

        $sujeito->adicionarSessao($sessao2);
        $sujeito->adicionarSessao($sessao1);

        $service = new CicloDeObservacaoService();
        $resultado = $service->executar($sujeito, 'mem1');

        $this->assertSame([$sessao1, $sessao2], $resultado->memoria()->sessoes());

        $this->assertCount(1, $resultado->recorrencias());
        $this->assertSame('lapso', $resultado->recorrencias()[0]->descricao()->valor());
        $this->assertSame(2, $resultado->recorrencias()[0]->frequencia()->valor());

        $this->assertCount(1, $resultado->observacoes());
        $this->assertSame(
            'Recorrência observada: lapso (2 ocorrência(s)).',
            $resultado->observacoes()[0]->texto()->valor()
        );
    }
}
