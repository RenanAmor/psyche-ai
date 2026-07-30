<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\ViewModels;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\ViewModels\ConsolidacaoViewModel;
use PsycheAI\Presentation\Web\ViewModels\DashboardViewModel;
use PsycheAI\Presentation\Web\ViewModels\DiscursoViewModel;
use PsycheAI\Presentation\Web\ViewModels\EventoDiscursivoViewModel;
use PsycheAI\Presentation\Web\ViewModels\LinhaDoTempoItemViewModel;
use PsycheAI\Presentation\Web\ViewModels\MemoriaViewModel;
use PsycheAI\Presentation\Web\ViewModels\MensagemViewModel;
use PsycheAI\Presentation\Web\ViewModels\SessaoViewModel;
use PsycheAI\Presentation\Web\ViewModels\SujeitoViewModel;

final class ViewModelsTest extends TestCase
{
    public function testSujeitoViewModelFromArray(): void
    {
        $vm = SujeitoViewModel::fromArray(['id' => 'sub-001', 'nome' => 'Ana', 'quantidadeDeSessoes' => 3]);

        $this->assertSame('sub-001', $vm->id);
        $this->assertSame('Ana', $vm->nome);
        $this->assertSame(3, $vm->quantidadeDeSessoes);
    }

    public function testSujeitoViewModelFromArrayAplicaPadroesParaCamposAusentes(): void
    {
        $vm = SujeitoViewModel::fromArray([]);

        $this->assertSame('', $vm->id);
        $this->assertSame('', $vm->nome);
        $this->assertSame(0, $vm->quantidadeDeSessoes);
    }

    public function testSujeitoViewModelFromArrayList(): void
    {
        $lista = SujeitoViewModel::fromArrayList([
            ['id' => '1', 'nome' => 'A', 'quantidadeDeSessoes' => 1],
            ['id' => '2', 'nome' => 'B', 'quantidadeDeSessoes' => 2],
        ]);

        $this->assertCount(2, $lista);
        $this->assertSame('A', $lista[0]->nome);
        $this->assertSame('B', $lista[1]->nome);
    }

    public function testSessaoViewModelFromArray(): void
    {
        $vm = SessaoViewModel::fromArray(['id' => 'ses-1', 'data' => '2026-01-01', 'quantidadeDeDiscursos' => 2]);

        $this->assertSame('ses-1', $vm->id);
        $this->assertSame('2026-01-01', $vm->data);
        $this->assertSame(2, $vm->quantidadeDeDiscursos);
    }

    public function testDiscursoViewModelFromArray(): void
    {
        $vm = DiscursoViewModel::fromArray(['id' => 'dsc-1', 'conteudo' => 'texto', 'quantidadeDeEventos' => 4]);

        $this->assertSame('dsc-1', $vm->id);
        $this->assertSame('texto', $vm->conteudo);
        $this->assertSame(4, $vm->quantidadeDeEventos);
    }

    public function testMemoriaViewModelFromArray(): void
    {
        $vm = MemoriaViewModel::fromArray(['id' => 'mem-1', 'quantidadeDeSessoes' => 5]);

        $this->assertSame('mem-1', $vm->id);
        $this->assertSame(5, $vm->quantidadeDeSessoes);
    }

    public function testEventoDiscursivoViewModelFromArray(): void
    {
        $vm = EventoDiscursivoViewModel::fromArray(['id' => 'evt-1', 'conteudo' => 'chiste', 'posicao' => 3]);

        $this->assertSame('evt-1', $vm->id);
        $this->assertSame('chiste', $vm->conteudo);
        $this->assertSame(3, $vm->posicao);
    }

    public function testMensagemViewModelMarcaPosicaoParComoAutorVoceEImparComoSistema(): void
    {
        $usuario = MensagemViewModel::fromArray(['id' => 'evt-1', 'conteudo' => 'Olá', 'posicao' => 0]);
        $sistema = MensagemViewModel::fromArray(['id' => 'evt-2', 'conteudo' => 'Continue', 'posicao' => 1]);

        $this->assertSame('Você', $usuario->autor);
        $this->assertSame('Sistema', $sistema->autor);
    }

    public function testHistoricoDaSessaoFiltraPorSessaoIdEOrdenaPorPosicao(): void
    {
        $eventos = [
            ['id' => 'evt-3', 'conteudo' => 'Fora de ordem', 'posicao' => 2, 'sessaoId' => 'ses-1'],
            ['id' => 'evt-outra-sessao', 'conteudo' => 'Não deve aparecer', 'posicao' => 0, 'sessaoId' => 'ses-2'],
            ['id' => 'evt-1', 'conteudo' => 'Primeira', 'posicao' => 0, 'sessaoId' => 'ses-1'],
            ['id' => 'evt-2', 'conteudo' => 'Segunda', 'posicao' => 1, 'sessaoId' => 'ses-1'],
        ];

        $historico = MensagemViewModel::historicoDaSessao($eventos, 'ses-1');

        $this->assertCount(3, $historico);
        $this->assertSame(['evt-1', 'evt-2', 'evt-3'], array_map(static fn ($m) => $m->id, $historico));
    }

    public function testLinhaDoTempoItemViewModelFromArraySessao(): void
    {
        $vm = LinhaDoTempoItemViewModel::fromArray([
            'tipo' => 'sessao',
            'id' => 'ses-1',
            'timestamp' => '2026-01-10 10:00:00',
            'dados' => ['id' => 'ses-1', 'data' => '2026-01-10 10:00:00', 'quantidadeDeDiscursos' => 2],
        ]);

        $this->assertSame('sessao', $vm->tipo);
        $this->assertSame('ses-1', $vm->id);
        $this->assertSame('Sessão', $vm->rotulo());
        $this->assertSame('2 discurso(s) registrado(s).', $vm->resumo());
        $this->assertSame('/sessoes/ses-1', $vm->rotaDetalhe());
    }

    public function testLinhaDoTempoItemViewModelFromArrayEvento(): void
    {
        $vm = LinhaDoTempoItemViewModel::fromArray([
            'tipo' => 'evento',
            'id' => 'evt-1',
            'timestamp' => '2026-01-10 10:05:00',
            'dados' => ['conteudo' => 'Lapso'],
        ]);

        $this->assertSame('Evento Discursivo', $vm->rotulo());
        $this->assertSame('Lapso', $vm->resumo());
        $this->assertNull($vm->rotaDetalhe());
    }

    public function testLinhaDoTempoItemViewModelFromArrayMemoria(): void
    {
        $vm = LinhaDoTempoItemViewModel::fromArray([
            'tipo' => 'memoria',
            'id' => 'mem-1',
            'timestamp' => '2026-01-20 10:00:00',
            'dados' => ['quantidadeDeSessoes' => 3],
        ]);

        $this->assertSame('Memória Longitudinal', $vm->rotulo());
        $this->assertSame('Consolida 3 sessão(ões).', $vm->resumo());
        $this->assertSame('/memorias/mem-1', $vm->rotaDetalhe());
    }

    public function testLinhaDoTempoItemViewModelFromArrayList(): void
    {
        $lista = LinhaDoTempoItemViewModel::fromArrayList([
            ['tipo' => 'sessao', 'id' => 'ses-1', 'timestamp' => '2026-01-10', 'dados' => []],
            ['tipo' => 'discurso', 'id' => 'dsc-1', 'timestamp' => '2026-01-10', 'dados' => ['conteudo' => 'texto']],
        ]);

        $this->assertCount(2, $lista);
        $this->assertSame('dsc-1', $lista[1]->id);
        $this->assertSame('/discursos/dsc-1', $lista[1]->rotaDetalhe());
    }

    public function testConsolidacaoViewModelFromArray(): void
    {
        $vm = ConsolidacaoViewModel::fromArray([
            'sujeitoId' => 'sub-1',
            'quantidadeDeSessoes' => 2,
            'quantidadeDeDiscursos' => 3,
            'quantidadeDeEventosDiscursivos' => 5,
            'quantidadeDeMemorias' => 1,
        ]);

        $this->assertSame('sub-1', $vm->sujeitoId);
        $this->assertSame(2, $vm->quantidadeDeSessoes);
        $this->assertSame(3, $vm->quantidadeDeDiscursos);
        $this->assertSame(5, $vm->quantidadeDeEventosDiscursivos);
        $this->assertSame(1, $vm->quantidadeDeMemorias);
    }

    public function testDashboardViewModelContaCadaLista(): void
    {
        $dashboard = DashboardViewModel::fromListas(
            [['id' => '1'], ['id' => '2']],
            [['id' => '1']],
            [],
            [['id' => '1'], ['id' => '2'], ['id' => '3']],
            []
        );

        $this->assertSame(2, $dashboard->totalSujeitos);
        $this->assertSame(1, $dashboard->totalSessoes);
        $this->assertSame(0, $dashboard->totalDiscursos);
        $this->assertSame(3, $dashboard->totalMemorias);
        $this->assertSame(0, $dashboard->totalEventosDiscursivos);
    }
}
