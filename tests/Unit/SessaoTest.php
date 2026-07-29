<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Discurso;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\ValueObjects\ConteudoDiscursivo;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;

final class SessaoTest extends TestCase
{
    public function testExposesData(): void
    {
        $data = new DataSessao(new \DateTimeImmutable('2026-07-01 10:00:00'));
        $sessao = new Sessao(new Identificador('s1'), $data);

        $this->assertTrue($data->equals($sessao->data()));
    }

    public function testAcumulaDiscursos(): void
    {
        $sessao = new Sessao(new Identificador('s1'), new DataSessao(new \DateTimeImmutable()));
        $discurso = new Discurso(new Identificador('d1'), new ConteudoDiscursivo('conteúdo'));

        $sessao->adicionarDiscurso($discurso);

        $this->assertSame([$discurso], $sessao->discursos());
    }
}
