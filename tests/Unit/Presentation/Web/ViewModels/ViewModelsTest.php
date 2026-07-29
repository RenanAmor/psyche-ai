<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\ViewModels;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\ViewModels\DashboardViewModel;
use PsycheAI\Presentation\Web\ViewModels\DiscursoViewModel;
use PsycheAI\Presentation\Web\ViewModels\EventoDiscursivoViewModel;
use PsycheAI\Presentation\Web\ViewModels\MemoriaViewModel;
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
