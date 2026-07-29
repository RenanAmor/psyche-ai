<?php

declare(strict_types=1);

namespace PsycheAI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PsycheAI\Domain\Entities\Sessao;
use PsycheAI\Domain\Services\OrganizadorMemoria;
use PsycheAI\Domain\ValueObjects\DataSessao;
use PsycheAI\Domain\ValueObjects\Identificador;

final class OrganizadorMemoriaTest extends TestCase
{
    public function testOrdenaSessoesCronologicamente(): void
    {
        $sessaoRecente = new Sessao(new Identificador('s2'), new DataSessao(new \DateTimeImmutable('2026-02-01')));
        $sessaoAntiga = new Sessao(new Identificador('s1'), new DataSessao(new \DateTimeImmutable('2026-01-01')));

        $ordenadas = (new OrganizadorMemoria())->organizar([$sessaoRecente, $sessaoAntiga]);

        $this->assertSame([$sessaoAntiga, $sessaoRecente], $ordenadas);
    }
}
