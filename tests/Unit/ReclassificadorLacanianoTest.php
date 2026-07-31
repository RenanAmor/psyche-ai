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
use PsycheAI\Domain\ValueObjects\TipoFormacaoFreudiana;

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

    public function testReclassificarPorTipoFreudianoRotulaChisteESonhoComoMetafora(): void
    {
        $reclassificador = new ReclassificadorLacaniano();

        $this->assertSame(
            'Estrutura candidata: metáfora — condensação.',
            $reclassificador->reclassificarPorTipoFreudiano(TipoFormacaoFreudiana::Chiste)
        );
        $this->assertSame(
            'Estrutura candidata: metáfora — condensação.',
            $reclassificador->reclassificarPorTipoFreudiano(TipoFormacaoFreudiana::Sonho)
        );
    }

    public function testReclassificarPorTipoFreudianoRotulaAtoFalhoERepeticaoComoDeslizeMetonimico(): void
    {
        $reclassificador = new ReclassificadorLacaniano();

        $this->assertSame(
            'Estrutura candidata: deslize metonímico.',
            $reclassificador->reclassificarPorTipoFreudiano(TipoFormacaoFreudiana::AtoFalho)
        );
        $this->assertSame(
            'Estrutura candidata: deslize metonímico.',
            $reclassificador->reclassificarPorTipoFreudiano(TipoFormacaoFreudiana::Repeticao)
        );
    }

    public function testReclassificarPorTipoFreudianoRotulaFormacaoDeCompromissoComoIndeterminadoEntreAsDuas(): void
    {
        $rotulo = (new ReclassificadorLacaniano())
            ->reclassificarPorTipoFreudiano(TipoFormacaoFreudiana::FormacaoDeCompromisso);

        $this->assertSame(
            'Estrutura candidata: formação de compromisso — a determinar entre metáfora e metonímia.',
            $rotulo
        );
    }

    public function testReclassificarPorTipoFreudianoRotulaNaoClassificadoComoDeslizeMetonimico(): void
    {
        $rotulo = (new ReclassificadorLacaniano())
            ->reclassificarPorTipoFreudiano(TipoFormacaoFreudiana::NaoClassificado);

        $this->assertSame('Estrutura candidata: deslize metonímico.', $rotulo);
    }

    public function testFundamentacaoParaDevolveATeoriaParaCadaUmDosQuatroRotulos(): void
    {
        $reclassificador = new ReclassificadorLacaniano();

        $this->assertStringContainsString(
            'Deslize metonímico',
            $reclassificador->fundamentacaoPara('Estrutura candidata: deslize metonímico.')
        );
        $this->assertStringContainsString(
            'Circuito',
            $reclassificador->fundamentacaoPara('Estrutura candidata: circuito — o tema retorna ao mesmo ponto através de sessões distintas.')
        );
        $this->assertStringContainsString(
            'Metáfora',
            $reclassificador->fundamentacaoPara('Estrutura candidata: metáfora — condensação.')
        );
        $this->assertStringContainsString(
            'Formação de compromisso',
            $reclassificador->fundamentacaoPara('Estrutura candidata: formação de compromisso — a determinar entre metáfora e metonímia.')
        );
    }

    public function testFundamentacaoParaDevolveStringVaziaParaRotuloDesconhecido(): void
    {
        $this->assertSame('', (new ReclassificadorLacaniano())->fundamentacaoPara('rótulo qualquer'));
    }
}
