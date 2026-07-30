<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Recorrencia;
use PsycheAI\Domain\Services\ReclassificadorLacaniano;
use PsycheAI\Domain\ValueObjects\Frequencia;
use PsycheAI\Domain\ValueObjects\Identificador;
use PsycheAI\Domain\ValueObjects\OcorrenciaRecorrencia;
use PsycheAI\Domain\ValueObjects\Texto;

final class ReclassificadorLacanianoTest extends TestCase
{
    public function testRotulaCadaRecorrenciaComoEstruturaCandidataDeDeslizeMetonimico(): void
    {
        $recorrencias = [
            new Recorrencia(new Identificador('r1'), new Texto('lapso'), new Frequencia(2)),
            new Recorrencia(new Identificador('r2'), new Texto('chiste'), new Frequencia(3)),
        ];

        $rotulos = (new ReclassificadorLacaniano())->reclassificar($recorrencias);

        $this->assertSame(
            [
                'r1' => 'Estrutura candidata: deslize metonímico.',
                'r2' => 'Estrutura candidata: deslize metonímico.',
            ],
            $rotulos
        );
    }

    public function testNaoAcrescentaRecorrenciaNemAlteraOsDadosOriginais(): void
    {
        $recorrencia = new Recorrencia(new Identificador('r1'), new Texto('lapso'), new Frequencia(2));

        (new ReclassificadorLacaniano())->reclassificar([$recorrencia]);

        $this->assertSame('lapso', $recorrencia->descricao()->valor());
        $this->assertSame(2, $recorrencia->frequencia()->valor());
    }

    public function testDevolveArrayVazioParaListaVazia(): void
    {
        $this->assertSame([], (new ReclassificadorLacaniano())->reclassificar([]));
    }

    private function ocorrencia(string $sessaoId): OcorrenciaRecorrencia
    {
        return new OcorrenciaRecorrencia($sessaoId, 'discurso-1', 'evento-1', new DateTimeImmutable('2026-01-10'), 0);
    }

    public function testReclassificarComTrajetoRotulaComoDeslizeMetonimicoQuandoUmaUnicaSessao(): void
    {
        $recorrencia = new Recorrencia(new Identificador('r1'), new Texto('lapso'), new Frequencia(2));

        $rotulos = (new ReclassificadorLacaniano())->reclassificarComTrajeto(
            [$recorrencia],
            ['r1' => [$this->ocorrencia('sessao-1'), $this->ocorrencia('sessao-1')]]
        );

        $this->assertSame(
            ['r1' => 'Estrutura candidata: deslize metonímico.'],
            $rotulos
        );
    }

    public function testReclassificarComTrajetoRotulaComoCircuitoQuandoDuasSessoesDistintas(): void
    {
        $recorrencia = new Recorrencia(new Identificador('r1'), new Texto('lapso'), new Frequencia(2));

        $rotulos = (new ReclassificadorLacaniano())->reclassificarComTrajeto(
            [$recorrencia],
            ['r1' => [$this->ocorrencia('sessao-1'), $this->ocorrencia('sessao-2')]]
        );

        $this->assertSame(
            ['r1' => 'Estrutura candidata: circuito — o tema retorna ao mesmo ponto através de sessões distintas.'],
            $rotulos
        );
    }

    public function testReclassificarComTrajetoRotulaComoDeslizeMetonimicoQuandoNaoHaCircuitoCorrespondente(): void
    {
        $recorrencia = new Recorrencia(new Identificador('r1'), new Texto('lapso'), new Frequencia(2));

        $rotulos = (new ReclassificadorLacaniano())->reclassificarComTrajeto([$recorrencia], []);

        $this->assertSame(
            ['r1' => 'Estrutura candidata: deslize metonímico.'],
            $rotulos
        );
    }

    public function testReclassificarComTrajetoNaoAlteraOComportamentoDeReclassificar(): void
    {
        $recorrencia = new Recorrencia(new Identificador('r1'), new Texto('lapso'), new Frequencia(2));

        (new ReclassificadorLacaniano())->reclassificarComTrajeto(
            [$recorrencia],
            ['r1' => [$this->ocorrencia('sessao-1'), $this->ocorrencia('sessao-2')]]
        );

        $rotulosSemTrajeto = (new ReclassificadorLacaniano())->reclassificar([$recorrencia]);

        $this->assertSame(
            ['r1' => 'Estrutura candidata: deslize metonímico.'],
            $rotulosSemTrajeto
        );
    }
}
