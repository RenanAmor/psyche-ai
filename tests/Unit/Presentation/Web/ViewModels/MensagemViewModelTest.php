<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit\Presentation\Web\ViewModels;

use PHPUnit\Framework\TestCase;
use PsycheAI\Presentation\Web\ViewModels\MensagemViewModel;

final class MensagemViewModelTest extends TestCase
{
    public function testMapeiaLocutorParaORotuloDeExibicao(): void
    {
        $sujeito = MensagemViewModel::fromArray(['id' => 'e1', 'conteudo' => 'oi', 'posicao' => 0, 'locutor' => 'sujeito']);
        $sistema = MensagemViewModel::fromArray(['id' => 'e2', 'conteudo' => 'olá', 'posicao' => 1, 'locutor' => 'sistema']);
        $analista = MensagemViewModel::fromArray(['id' => 'e3', 'conteudo' => 'nota', 'posicao' => 2, 'locutor' => 'analista']);

        $this->assertSame('Você', $sujeito->autor);
        $this->assertSame('Sistema', $sistema->autor);
        $this->assertSame('Analista', $analista->autor);
    }

    public function testNaoInfereAutorPelaParidadeDaPosicao(): void
    {
        // Regressão: antes da Videochamada Embutida, posição ímpar com
        // locutor "sujeito" (ex.: segundo segmento de uma gravação
        // contínua do mesmo falante) era rotulada incorretamente como
        // "Sistema" só por ser ímpar.
        $evento = MensagemViewModel::fromArray(['id' => 'e1', 'conteudo' => 'segundo trecho', 'posicao' => 1, 'locutor' => 'sujeito']);

        $this->assertSame('Você', $evento->autor);
    }

    public function testLocutorDesconhecidoOuAusenteVieraComoRotuloDesconhecido(): void
    {
        $evento = MensagemViewModel::fromArray(['id' => 'e1', 'conteudo' => 'x', 'posicao' => 0]);

        $this->assertSame('Desconhecido', $evento->autor);
    }
}
